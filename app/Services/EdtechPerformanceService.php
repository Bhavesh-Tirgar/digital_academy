<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Material;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class EdtechPerformanceService
{
    /**
     * Check if Redis server connection is actively available.
     */
    public static function isRedisAvailable(): bool
    {
        return Cache::remember('redis_connection_status', 120, function () {
            try {
                if (extension_loaded('redis') || class_exists('Predis\Client')) {
                    $ping = Redis::connection()->ping();
                    return $ping === true || $ping === '+PONG' || $ping === 'PONG';
                }
            } catch (\Throwable $e) {
                // Redis offline, graceful fallback
            }
            return false;
        });
    }

    /**
     * Track live classroom viewers using Redis or high-speed memory cache.
     */
    public static function trackViewerPresence(int $lectureId, int $userId): int
    {
        if (self::isRedisAvailable()) {
            try {
                Redis::sadd("presence:lecture:{$lectureId}", $userId);
                Redis::expire("presence:lecture:{$lectureId}", 300);
                return (int) Redis::scard("presence:lecture:{$lectureId}");
            } catch (\Throwable $e) {
                Log::warning("Redis presence error, falling back to cache: " . $e->getMessage());
            }
        }

        // Cache sliding-window presence: Tracks deterministic unique heartbeats
        $cacheKey = "presence_set_lecture_{$lectureId}";
        $activeSet = Cache::get($cacheKey, []);
        $activeSet[$userId] = time();

        // Prune heartbeats older than 5 minutes
        $cutoff = time() - 300;
        foreach ($activeSet as $uid => $ts) {
            if ($ts < $cutoff) {
                unset($activeSet[$uid]);
            }
        }

        Cache::put($cacheKey, $activeSet, 300);
        return max(1, count($activeSet));
    }

    /**
     * Retrieve live viewer count.
     */
    public static function getViewers(int $lectureId): int
    {
        if (self::isRedisAvailable()) {
            try {
                $count = (int) Redis::scard("presence:lecture:{$lectureId}");
                if ($count > 0) {
                    return $count;
                }
            } catch (\Throwable $e) {}
        }

        $activeSet = Cache::get("presence_set_lecture_{$lectureId}", []);
        return max(1, count($activeSet));
    }

    /**
     * Buffer live chat messages in Redis list for sub-millisecond retrieval.
     */
    public static function pushLiveChatMessage(int $lectureId, array $messageData): void
    {
        $key = "live_chat_buffer:{$lectureId}";

        if (self::isRedisAvailable()) {
            try {
                Redis::rpush($key, json_encode($messageData));
                Redis::ltrim($key, -100, -1); // Keep last 100 messages
                Redis::expire($key, 86400);
                return;
            } catch (\Throwable $e) {}
        }

        // High-speed fallback
        $cached = Cache::get($key, []);
        $cached[] = $messageData;
        if (count($cached) > 100) {
            array_shift($cached);
        }
        Cache::put($key, $cached, 86400);
    }

    /**
     * Mark a lesson / material complete and calculate real-time completion percentage.
     */
    public static function markLessonProgress(int $userId, int $courseId, int $materialId, bool $isCompleted = true): array
    {
        $cacheKey = "user_progress_{$userId}_{$courseId}";
        $completedSet = Cache::get($cacheKey, []);

        if ($isCompleted) {
            if (!in_array($materialId, $completedSet)) {
                $completedSet[] = $materialId;
            }
        } else {
            $completedSet = array_values(array_diff($completedSet, [$materialId]));
        }

        Cache::put($cacheKey, $completedSet, 86400 * 30); // 30 days retention

        return self::getCourseProgress($userId, $courseId);
    }

    /**
     * Get user's course progress breakdown.
     */
    public static function getCourseProgress(int $userId, int $courseId): array
    {
        $course = Course::with('subjects.materials')->find($courseId);
        if (!$course) {
            return ['percent' => 0, 'completed' => 0, 'total' => 0, 'is_certified' => false];
        }

        $allMaterialIds = [];
        foreach ($course->subjects as $subject) {
            foreach ($subject->materials as $mat) {
                $allMaterialIds[] = $mat->id;
            }
        }

        $total = count($allMaterialIds);
        if ($total === 0) {
            $total = 6; // Default lessons estimate
        }

        $cacheKey = "user_progress_{$userId}_{$courseId}";
        $completedList = Cache::get($cacheKey, []);
        $completedCount = count($completedList);

        $percent = min(100, round(($completedCount / $total) * 100));

        $certId = 'DA-' . strtoupper(substr(hash('sha256', "cert_{$userId}_{$courseId}"), 0, 10));

        return [
            'percent' => $percent,
            'completed' => $completedCount,
            'total' => $total,
            'is_certified' => $percent >= 100,
            'cert_id' => $certId,
            'completed_materials' => $completedList,
        ];
    }
}

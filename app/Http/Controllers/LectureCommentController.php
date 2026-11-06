<?php

namespace App\Http\Controllers;

use App\Models\LectureComment;
use App\Models\Course;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureCommentController extends Controller
{
    /**
     * Store student comment or instructor reply.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please log in to join the lecture discussion.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:lecture_comments,id',
            'lecture_id' => 'nullable|exists:live_lectures,id',
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        $isInstructorReply = false;
        if ($course->instructor_id === $user->id || $user->isAdmin() || $user->isInstructor()) {
            $isInstructorReply = true;
        }

        $comment = LectureComment::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'lecture_id' => $request->lecture_id,
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
            'code_snippet' => $request->input('code_snippet'),
            'is_instructor_reply' => $isInstructorReply,
        ]);

        // If an instructor replies, notify the student!
        if ($isInstructorReply && $request->parent_id) {
            $parent = LectureComment::find($request->parent_id);
            if ($parent && $parent->user_id !== $user->id) {
                Notification::create([
                    'user_id' => $parent->user_id,
                    'title' => '👨‍🏫 Instructor Replied to Your Question!',
                    'message' => "{$user->name} replied: \"" . \Illuminate\Support\Str::limit($request->comment, 60) . "\"",
                    'type' => 'lecture',
                    'link' => route('courses.detail', $course->id) . '#discussion',
                ]);
            }
        } elseif (!$isInstructorReply && $course->instructor_id) {
            // Notify the instructor about new student question
            Notification::create([
                'user_id' => $course->instructor_id,
                'title' => '❓ New Student Question in ' . $course->name,
                'message' => "{$user->name} asked: \"" . \Illuminate\Support\Str::limit($request->comment, 60) . "\"",
                'type' => 'lecture',
                'link' => route('courses.detail', $course->id) . '#discussion',
            ]);
        }

        return back()->with('success', $isInstructorReply ? 'Instructor reply posted successfully!' : 'Your question/comment has been posted to the discussion.');
    }

    /**
     * Delete comment.
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Unauthorized.');
        }

        $comment = LectureComment::findOrFail($id);
        $user = Auth::user();

        if ($comment->user_id === $user->id || $user->isAdmin() || $comment->course->instructor_id === $user->id) {
            $comment->delete();
            return back()->with('success', 'Comment deleted successfully.');
        }

        return back()->with('error', 'You do not have permission to delete this comment.');
    }

    /**
     * Mark reply as Accepted Solution / Resolved Doubt.
     */
    public function acceptAnswer($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $reply = LectureComment::with('course')->findOrFail($id);
        $user = Auth::user();

        // Only the question author, course instructor, or admin can accept
        $parent = $reply->parent_id ? LectureComment::find($reply->parent_id) : null;
        $isAuthor = $parent && $parent->user_id === $user->id;
        $isInstructor = $reply->course && ($reply->course->instructor_id === $user->id || $user->isAdmin());

        if (!$isAuthor && !$isInstructor) {
            return response()->json(['error' => 'Only the question author or instructor can mark accepted solutions.'], 403);
        }

        $reply->is_accepted_answer = !$reply->is_accepted_answer;
        $reply->save();

        return response()->json([
            'status' => 'success',
            'is_accepted' => $reply->is_accepted_answer,
            'message' => $reply->is_accepted_answer ? 'Marked as accepted solution! ✅' : 'Solution unaccepted.',
        ]);
    }

    /**
     * Upvote / Like a helpful question or answer.
     */
    public function toggleLike($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please sign in to upvote.'], 401);
        }

        $comment = LectureComment::findOrFail($id);
        $comment->increment('likes_count');

        return response()->json([
            'status' => 'success',
            'likes_count' => $comment->likes_count,
        ]);
    }
}

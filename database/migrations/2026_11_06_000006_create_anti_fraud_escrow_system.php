<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Add escrow tracking columns to enrollments
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'payout_status')) {
                $table->string('payout_status')->default('escrow_held')->after('status');
            }
            if (!Schema::hasColumn('enrollments', 'escrow_released_at')) {
                $table->timestamp('escrow_released_at')->nullable()->after('payout_status');
            }
        });

        // 2. Add course completion and fraud verification columns to courses
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'is_completed')) {
                $table->boolean('is_completed')->default(false)->after('enrolled_count');
            }
            if (!Schema::hasColumn('courses', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('is_completed');
            }
        });

        // 3. Add escrow balance to users (instructors)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'escrow_balance')) {
                $table->decimal('escrow_balance', 10, 2)->default(0.00)->after('balance');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'escrow_balance')) {
                $table->dropColumn('escrow_balance');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            if (Schema::hasColumn('courses', 'is_completed')) {
                $table->dropColumn('is_completed');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'escrow_released_at')) {
                $table->dropColumn('escrow_released_at');
            }
            if (Schema::hasColumn('enrollments', 'payout_status')) {
                $table->dropColumn('payout_status');
            }
        });
    }
};

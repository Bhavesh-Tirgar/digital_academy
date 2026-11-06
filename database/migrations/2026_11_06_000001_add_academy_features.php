<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Enhance users table for instructors & profiles
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'headline')) {
                $table->string('headline')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('headline');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0.00)->after('avatar');
            }
            if (!Schema::hasColumn('users', 'earnings')) {
                $table->decimal('earnings', 10, 2)->default(0.00)->after('balance');
            }
        });

        // 2. Enhance courses table with details, teacher, price & metrics
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('courses', 'instructor_id')) {
                $table->foreignId('instructor_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('courses', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('instructor_id')->constrained('categories')->nullOnDelete();
            }
            if (!Schema::hasColumn('courses', 'price')) {
                $table->decimal('price', 8, 2)->default(49.00)->after('description');
            }
            if (!Schema::hasColumn('courses', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('price');
            }
            if (!Schema::hasColumn('courses', 'demo_video_url')) {
                $table->string('demo_video_url')->nullable()->after('thumbnail');
            }
            if (!Schema::hasColumn('courses', 'level')) {
                $table->string('level')->default('Beginner')->after('demo_video_url');
            }
            if (!Schema::hasColumn('courses', 'duration')) {
                $table->string('duration')->default('8 Weeks')->after('level');
            }
            if (!Schema::hasColumn('courses', 'enrolled_count')) {
                $table->unsignedInteger('enrolled_count')->default(120)->after('duration');
            }
            if (!Schema::hasColumn('courses', 'rating')) {
                $table->decimal('rating', 3, 1)->default(4.9)->after('enrolled_count');
            }
            if (!Schema::hasColumn('courses', 'what_you_will_learn')) {
                $table->text('what_you_will_learn')->nullable()->after('rating');
            }
        });

        // 3. Create Enrollments table (Tracking purchases, teacher share & platform fee)
        if (!Schema::hasTable('enrollments')) {
            Schema::create('enrollments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
                $table->string('plan')->default('Premium');
                $table->decimal('amount_paid', 8, 2)->default(49.00);
                $table->decimal('platform_fee', 8, 2)->default(9.80); // 20% platform fee
                $table->decimal('instructor_earning', 8, 2)->default(39.20); // 80% instructor share
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // 4. Create Live Lectures table (Schedule, live badge, meeting room)
        if (!Schema::hasTable('live_lectures')) {
            Schema::create('live_lectures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
                $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->dateTime('start_time');
                $table->dateTime('end_time')->nullable();
                $table->string('meeting_link')->nullable();
                $table->enum('status', ['scheduled', 'live', 'completed'])->default('scheduled');
                $table->boolean('is_demo')->default(false);
                $table->timestamps();
            });
        }

        // 5. Create E-Books table (E-book library slot for students)
        if (!Schema::hasTable('ebooks')) {
            Schema::create('ebooks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('author');
                $table->text('description')->nullable();
                $table->string('cover_image')->nullable();
                $table->string('file_path')->nullable();
                $table->unsignedInteger('pages')->default(120);
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->unsignedInteger('downloads_count')->default(450);
                $table->boolean('is_free')->default(true);
                $table->timestamps();
            });
        }

        // 6. Create Notifications table (Live lecture alerts, announcements)
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('message');
                $table->string('type')->default('lecture'); // lecture, course, payment, system
                $table->string('link')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('ebooks');
        Schema::dropIfExists('live_lectures');
        Schema::dropIfExists('enrollments');
    }
};

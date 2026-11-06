<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('lecture_comments')) {
            Schema::create('lecture_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('lecture_id')->nullable()->constrained('live_lectures')->nullOnDelete();
                $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
                $table->foreignId('parent_id')->nullable()->constrained('lecture_comments')->onDelete('cascade');
                $table->text('comment');
                $table->boolean('is_instructor_reply')->default(false);
                $table->unsignedInteger('likes_count')->default(0);
                $table->timestamps();

                $table->index(['course_id', 'created_at']);
                $table->index(['parent_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lecture_comments');
    }
};

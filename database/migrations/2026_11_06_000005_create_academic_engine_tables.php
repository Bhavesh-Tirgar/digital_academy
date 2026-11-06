<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for academic grading, quizzes, video intelligence, and Q&A triage.
     */
    public function up(): void
    {
        // 1. Quizzes Table
        if (!Schema::hasTable('quizzes')) {
            Schema::create('quizzes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained()->cascadeOnDelete();
                $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->integer('pass_percentage')->default(80);
                $table->integer('time_limit_minutes')->default(15);
                $table->boolean('is_final_exam')->default(false);
                $table->timestamps();
            });
        }

        // 2. Quiz Questions Table
        if (!Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
                $table->text('question');
                $table->text('options'); // JSON encoded array of choices
                $table->integer('correct_option'); // 0-based index
                $table->text('explanation')->nullable();
                $table->timestamps();
            });
        }

        // 3. Quiz Attempts & Submissions Table
        if (!Schema::hasTable('quiz_attempts')) {
            Schema::create('quiz_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
                $table->integer('score')->default(0);
                $table->integer('total_questions')->default(0);
                $table->float('percentage')->default(0.0);
                $table->boolean('passed')->default(false);
                $table->text('answers')->nullable(); // JSON answers
                $table->timestamps();
            });
        }

        // 4. Enhance Materials with Video Intelligence & Transcripts
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'duration_seconds')) {
                $table->integer('duration_seconds')->default(720)->after('type');
            }
            if (!Schema::hasColumn('materials', 'video_url')) {
                $table->string('video_url')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('materials', 'transcript')) {
                $table->text('transcript')->nullable()->after('video_url');
            }
        });

        // 5. Enhance Lecture Comments with Accepted Answer & Code Snippets
        Schema::table('lecture_comments', function (Blueprint $table) {
            if (!Schema::hasColumn('lecture_comments', 'is_accepted_answer')) {
                $table->boolean('is_accepted_answer')->default(false)->after('is_instructor_reply');
            }
            if (!Schema::hasColumn('lecture_comments', 'code_snippet')) {
                $table->text('code_snippet')->nullable()->after('comment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('live_lectures', function (Blueprint $table) {
            if (!Schema::hasColumn('live_lectures', 'stream_url')) {
                $table->string('stream_url')->nullable()->after('meeting_link');
            }
            if (!Schema::hasColumn('live_lectures', 'active_viewers')) {
                $table->unsignedInteger('active_viewers')->default(148)->after('stream_url');
            }
            if (!Schema::hasColumn('live_lectures', 'live_poll')) {
                $table->text('live_poll')->nullable()->after('active_viewers');
            }
            if (!Schema::hasColumn('live_lectures', 'chat_enabled')) {
                $table->boolean('chat_enabled')->default(true)->after('live_poll');
            }
        });
    }

    public function down(): void
    {
        Schema::table('live_lectures', function (Blueprint $table) {
            $table->dropColumn(['stream_url', 'active_viewers', 'live_poll', 'chat_enabled']);
        });
    }
};

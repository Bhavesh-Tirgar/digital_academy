<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'subscription_type')) {
                $table->string('subscription_type')->nullable()->after('role'); // 'student' or 'instructor'
            }
            if (!Schema::hasColumn('users', 'subscribed_until')) {
                $table->dateTime('subscribed_until')->nullable()->after('subscription_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['subscription_type', 'subscribed_until']);
        });
    }
};

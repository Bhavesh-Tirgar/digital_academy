<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Alter the 'subjects' table to add a 'plan' column
        Schema::table('subjects', function (Blueprint $table) {
            // Adding 'plan' column of type string, which can be nullable
            $table->string('plan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the 'plan' column only if it exists
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'plan')) {
                $table->dropColumn('plan');
            }
        });
    }
};    

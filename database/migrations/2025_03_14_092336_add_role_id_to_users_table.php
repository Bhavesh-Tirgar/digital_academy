<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */


     public function up()
     {
         Schema::table('users', function (Blueprint $table) {
             $table->unsignedBigInteger('role_id')->after('password')->default(2); // Default: User role
             $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
         });
     }
     
     public function down()
     {
         Schema::table('users', function (Blueprint $table) {
             $table->dropForeign(['role_id']);
             $table->dropColumn('role_id');
         });
     }
     
};

<?php

namespace Database\Seeders; // ✅ Correct Namespace

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects')->insert([
            ['name' => 'Python', 'course_id' => 1],
            
        ]);
    }
}

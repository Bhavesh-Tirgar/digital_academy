<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects')->insertOrIgnore([
            ['id' => 1, 'name' => 'Python', 'course_id' => 1, 'plan' => 'Basic'],
            ['id' => 2, 'name' => 'Web Development', 'course_id' => 2, 'plan' => 'Standard'],
            ['id' => 3, 'name' => 'Machine Learning', 'course_id' => 3, 'plan' => 'Premium'],
        ]);
    }
}

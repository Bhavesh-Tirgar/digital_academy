<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run()
    {
        DB::table('courses')->insertOrIgnore([
            ['id' => 1, 'name' => 'Basic'],
            ['id' => 2, 'name' => 'Standard'],
            ['id' => 3, 'name' => 'Premium'],
        ]);
    }
}

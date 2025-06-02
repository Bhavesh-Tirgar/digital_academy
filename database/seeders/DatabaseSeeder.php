<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            CourseSeeder::class,
            SubjectSeeder::class,
         // Optional
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

require_once __DIR__ . '/AcademyFeatureSeeder.php';

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            CourseSeeder::class,
            SubjectSeeder::class,
            AcademyFeatureSeeder::class,
        ]);
    }
}

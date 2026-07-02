<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SiteSettingSeeder::class,
            MediaSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            BlogCategorySeeder::class,
            BlogSeeder::class,
            FaqSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}

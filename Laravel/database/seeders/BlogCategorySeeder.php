<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Tips Air Minum', 'slug' => 'tips-air-minum'],
            ['name' => 'Informasi LPG', 'slug' => 'informasi-lpg'],
            ['name' => 'Layanan Syahrizal Galon', 'slug' => 'layanan-syahrizal-galon'],
            ['name' => 'Edukasi Pelanggan', 'slug' => 'edukasi-pelanggan'],
            ['name' => 'Berita Usaha', 'slug' => 'berita-usaha'],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate(['slug' => $category['slug']], $category + ['is_active' => true]);
        }
    }
}

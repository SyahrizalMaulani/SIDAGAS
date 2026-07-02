<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Galon Isi Ulang', 'slug' => 'galon-isi-ulang', 'description' => 'Air minum isi ulang untuk kebutuhan rumah tangga, warung, dan UMKM.', 'sort_order' => 1],
            ['name' => 'LPG', 'slug' => 'lpg', 'description' => 'LPG 3 kg bersubsidi untuk kebutuhan memasak rumah tangga dan usaha kecil.', 'sort_order' => 2],
            ['name' => 'Air Kemasan', 'slug' => 'air-kemasan', 'description' => 'Air kemasan bermerek untuk pelanggan yang membutuhkan pilihan air minum premium.', 'sort_order' => 3],
            ['name' => 'Layanan Digital', 'slug' => 'layanan-digital', 'description' => 'Pulsa, paket data, token listrik, dan pembayaran tagihan.', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            ProductCategory::updateOrCreate(['slug' => $category['slug']], $category + ['is_active' => true]);
        }
    }
}

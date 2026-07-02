<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::query()->where('role', 'admin')->value('id');
        $categoryBySlug = ProductCategory::query()->get()->keyBy('slug');
        $mediaByPath = Media::query()->get()->keyBy('file_path');

        $products = [
            ['category' => 'galon-isi-ulang', 'name' => 'Galon Isi Ulang', 'slug' => 'galon-isi-ulang', 'short_description' => 'Air minum isi ulang untuk kebutuhan rumah tangga, warung, dan UMKM sekitar Indramayu.', 'price' => 5000, 'unit' => 'galon', 'image_path' => 'img/products/sampul-galon.webp', 'featured' => true, 'sort_order' => 1],
            ['category' => 'lpg', 'name' => 'Gas LPG 3 Kg', 'slug' => 'gas-lpg-3-kg', 'short_description' => 'LPG 3 kg bersubsidi untuk kebutuhan memasak rumah tangga dan usaha kecil.', 'price' => 22000, 'unit' => 'tabung', 'image_path' => 'img/products/sampul-gas.webp', 'featured' => true, 'sort_order' => 2],
            ['category' => 'air-kemasan', 'name' => 'Aqua Galon', 'slug' => 'aqua-galon', 'short_description' => 'Air kemasan bermerek untuk pelanggan yang membutuhkan pilihan air minum premium.', 'price' => 20000, 'unit' => 'galon', 'image_path' => 'img/products/sampul-kemasan.webp', 'featured' => false, 'sort_order' => 3],
            ['category' => 'layanan-digital', 'name' => 'Pulsa, Token Listrik, PDAM', 'slug' => 'layanan-digital', 'short_description' => 'Layanan digital pendamping untuk pulsa, paket data, token listrik, dan pembayaran tagihan.', 'price' => null, 'unit' => 'transaksi', 'image_path' => 'img/products/sampul-digital.webp', 'featured' => false, 'sort_order' => 4],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], [
                'product_category_id' => $categoryBySlug[$product['category']]->id,
                'name' => $product['name'],
                'short_description' => $product['short_description'],
                'description' => $product['short_description'],
                'price' => $product['price'],
                'unit' => $product['unit'],
                'image_id' => $mediaByPath[$product['image_path']]->id ?? null,
                'is_available' => true,
                'is_featured' => $product['featured'],
                'whatsapp_template' => "Halo Admin PD Syahrizal Galon, saya ingin memesan:\n\nProduk: {product}\nJumlah:\nNama:\nAlamat:\nCatatan:\n\nSaya mendapatkan informasi dari website SIDAGAS.",
                'sort_order' => $product['sort_order'],
                'created_by' => $adminId,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::query()->where('role', 'admin')->value('id');

        $items = [
            'galon' => ['file_name' => 'sampul-galon.webp', 'file_path' => 'img/products/sampul-galon.webp', 'alt_text' => 'Produk galon isi ulang PD Syahrizal Galon'],
            'gas' => ['file_name' => 'sampul-gas.webp', 'file_path' => 'img/products/sampul-gas.webp', 'alt_text' => 'Produk gas LPG 3 Kg PD Syahrizal Galon'],
            'kemasan' => ['file_name' => 'sampul-kemasan.webp', 'file_path' => 'img/products/sampul-kemasan.webp', 'alt_text' => 'Produk air kemasan PD Syahrizal Galon'],
            'digital' => ['file_name' => 'sampul-digital.webp', 'file_path' => 'img/products/sampul-digital.webp', 'alt_text' => 'Layanan digital PD Syahrizal Galon'],
        ];

        foreach ($items as $item) {
            Media::updateOrCreate(['file_path' => $item['file_path']], [
                'file_name' => $item['file_name'],
                'file_type' => 'image',
                'mime_type' => 'image/webp',
                'file_size' => 0,
                'alt_text' => $item['alt_text'],
                'created_by' => $adminId,
            ]);
        }
    }
}

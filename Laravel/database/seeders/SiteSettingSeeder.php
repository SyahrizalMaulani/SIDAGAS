<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'business_name', 'value' => 'PD Syahrizal Galon', 'type' => 'text', 'group' => 'general'],
            ['key' => 'whatsapp_number', 'value' => '6285224365449', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'whatsapp_display', 'value' => '0852-2436-5449', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'address', 'value' => 'Jln Raya Nasional 1 Kandanghaur - Indramayu, Desa Ilir, Kandanghaur, Indramayu, Jawa Barat 45254', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'address_short', 'value' => 'Desa Ilir, Kandanghaur, Indramayu', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'opening_hours', 'value' => '07.00 - 17.00 WIB', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'google_maps_url', 'value' => 'https://maps.app.goo.gl/m2kbdWmKGGNDr78z7', 'type' => 'url', 'group' => 'contact'],
            ['key' => 'google_maps_embed_url', 'value' => 'https://www.google.com/maps?q=PD%20Syahrizal%20Galon%20Desa%20Ilir%20Kandanghaur%20Indramayu&output=embed', 'type' => 'url', 'group' => 'contact'],
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com/syahrizal.maulani?locale=id_ID', 'type' => 'url', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => null, 'type' => 'url', 'group' => 'social'],
            ['key' => 'email_address', 'value' => null, 'type' => 'text', 'group' => 'social'],
            ['key' => 'home_meta_title', 'value' => 'PD Syahrizal Galon - Galon Isi Ulang dan LPG 3 Kg Indramayu', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'home_meta_description', 'value' => 'PD Syahrizal Galon menyediakan galon isi ulang, LPG 3 Kg, air kemasan, dan layanan digital untuk pelanggan rumah tangga, warung, dan UMKM di Indramayu.', 'type' => 'text', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

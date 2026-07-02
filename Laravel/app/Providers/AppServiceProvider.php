<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $settings = [];

        try {
            if (Schema::hasTable('site_settings')) {
                $settings = SiteSetting::query()->pluck('value', 'key')->all();
            }
        } catch (Throwable) {
            $settings = [];
        }

        View::share('siteSettings', $settings);
        View::share('siteWhatsappNumber', $settings['whatsapp_number'] ?? '6285224365449');
    }
}

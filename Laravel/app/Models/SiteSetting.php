<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    public static function value(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function whatsappNumber(): string
    {
        return static::value('whatsapp_number', '6285224365449');
    }

    public static function whatsappUrl(string $message = ''): string
    {
        $defaultMessage = 'Halo Admin PD Syahrizal Galon, saya ingin bertanya tentang layanan SIDAGAS.';

        return 'https://wa.me/' . static::whatsappNumber() . '?text=' . rawurlencode($message ?: $defaultMessage);
    }
}

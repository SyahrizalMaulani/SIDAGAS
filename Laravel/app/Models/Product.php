<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'unit',
        'image_id',
        'is_available',
        'is_featured',
        'whatsapp_template',
        'sort_order',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function whatsappClicks(): HasMany
    {
        return $this->hasMany(WhatsappClick::class);
    }

    public function priceLabel(): string
    {
        if ($this->price === null) {
            return 'Sesuai nominal';
        }

        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function statusLabel(): string
    {
        return $this->is_available ? 'Tersedia' : 'Tidak tersedia';
    }

    public function imagePath(): string
    {
        return $this->image?->file_path ?: 'img/products/sampul-galon.webp';
    }

    public function whatsappMessage(): string
    {
        $template = $this->whatsapp_template ?: "Halo Admin PD Syahrizal Galon, saya ingin memesan:\n\nProduk: {product}\nJumlah:\nNama:\nAlamat:\nCatatan:\n\nSaya mendapatkan informasi dari website SIDAGAS.";

        return strtr($template, [
            '{product}' => $this->name,
            '{price}' => $this->priceLabel(),
            '{unit}' => $this->unit,
        ]);
    }
}

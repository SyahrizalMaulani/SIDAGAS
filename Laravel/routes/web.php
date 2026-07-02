<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PublicSiteController::class, 'about'])->name('tentang-kami');
Route::get('/produk', [PublicSiteController::class, 'products'])->name('produk');
Route::get('/blog', [PublicSiteController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PublicSiteController::class, 'blogShow'])->name('blog.show');
Route::get('/kontak', [PublicSiteController::class, 'contact'])->name('kontak');
Route::post('/kontak', [PublicSiteController::class, 'storeContact'])->name('kontak.store')->middleware('throttle:5,1');
Route::get('/whatsapp', [PublicSiteController::class, 'whatsapp'])->name('whatsapp.click');
Route::get('/sitemap.xml', [PublicSiteController::class, 'sitemap'])->name('sitemap');

Route::redirect('/TentangKami', '/tentang-kami');
Route::redirect('/Produk', '/produk');
Route::redirect('/Kontak', '/kontak');
Route::redirect('/Blog', '/blog');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/orders', [AdminController::class, 'orders']);
    Route::get('/inventory', [AdminController::class, 'inventory']);
});

Route::middleware([RoleMiddleware::class . ':karyawan,admin'])->prefix('karyawan')->group(function () {
    Route::get('/', [KaryawanController::class, 'intake']);
    Route::get('/production', [KaryawanController::class, 'production']);
    Route::get('/ready', [KaryawanController::class, 'ready']);
});

Route::middleware([RoleMiddleware::class . ':driver'])->prefix('driver')->group(function () {
    Route::get('/', [DriverController::class, 'deliveries']);
    Route::get('/active', [DriverController::class, 'active']);
    Route::get('/history', [DriverController::class, 'history']);
});

Route::middleware([RoleMiddleware::class . ':pelanggan'])->prefix('pelanggan')->group(function () {
    Route::get('/', [PelangganController::class, 'catalog']);
    Route::get('/checkout', [PelangganController::class, 'checkout']);
    Route::get('/tracking', [PelangganController::class, 'tracking']);
});

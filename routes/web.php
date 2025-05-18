<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckRoleUser;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\JenisObatController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\JenisPengirimanController;
use App\Http\Controllers\ProfileUserController;
use App\Http\Controllers\PelangganManageController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\DetailPengirimanController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardPemilikController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\KeranjangController;

// ==================== Public Routes ====================
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/contactUs', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop');
Route::get('/shop/filter', [App\Http\Controllers\ShopController::class, 'filter'])->name('shop.filter');
Route::get('/product/{id}', [App\Http\Controllers\ShopController::class, 'show'])->name('product.detail');

// ==================== Midtrans Routes ====================
Route::post('/payment/callback', [MidtransController::class, 'handleCallback'])->name('payment.callback');
Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
Route::get('/payment/finish', [CheckoutController::class, 'paymentFinish'])->name('payment.finish');

// Routes Keranjang
Route::post('/keranjang/tambah', [KeranjangController::class, 'add'])->name('keranjang.add');
Route::get('/keranjang', [App\Http\Controllers\KeranjangController::class, 'index'])->name('keranjang');
Route::get('/keranjang/checkout', [App\Http\Controllers\KeranjangController::class, 'checkout'])->name('checkout');
Route::post('/keranjang/checkout/process', [App\Http\Controllers\KeranjangController::class, 'processCheckout'])->name('checkout.process');
Route::delete('/keranjang/remove/{id}', [App\Http\Controllers\KeranjangController::class, 'remove'])->name('keranjang.remove');
Route::post('/keranjang/update/{id}', [App\Http\Controllers\KeranjangController::class, 'update'])->name('keranjang.update');

// Auth User (Admin, Pemilik, Apoteker, Kasir, Karyawan)
Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'loginUser'])->name('login-user');
Route::get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'registerUser'])->name('register-user');

// Auth Pelanggan
Route::middleware('guest:pelanggan')->group(function () {
    Route::get('/signin', [App\Http\Controllers\PelangganController::class, 'signin'])->name('signin');
    Route::post('/signin', [App\Http\Controllers\PelangganController::class, 'signinUser'])->name('signin-user');
    Route::get('/signup', [App\Http\Controllers\PelangganController::class, 'signup'])->name('signup');
    Route::post('/signup', [App\Http\Controllers\PelangganController::class, 'signupUser'])->name('signup-user');
});
// Logout
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/signout', [App\Http\Controllers\PelangganController::class, 'signout'])->middleware('auth:pelanggan')->name('signout');

// ==================== Dashboard Redirect ====================
Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'pemilik' => redirect()->route('pemilik.dashboard'),
        'apoteker' => redirect()->route('apoteker.dashboard'),
        'karyawan' => redirect()->route('karyawan.dashboard'),
        'kasir' => redirect()->route('kasir.dashboard'),
        default => redirect()->route('login')->withErrors('Akses ditolak. Silakan login kembali.'),
    };
})->middleware('auth')->name('dashboard');

// ==================== Role-Based Dashboards ====================
Route::prefix('admin')->middleware(['auth', CheckRoleUser::class . ':admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
});

Route::prefix('pemilik')->middleware(['auth', CheckRoleUser::class . ':pemilik'])->group(function () {
    Route::get('/', [DashboardPemilikController::class, 'index'])->name('pemilik.dashboard');
    Route::get('/dashboard', [DashboardPemilikController::class, 'index'])->name('pemilik.dashboard');
    Route::get('/dashboard/export-pdf', [DashboardPemilikController::class, 'exportPdf'])->name('pemilik.dashboard.exportPdf');
    Route::get('/dashboard/export-excel', [DashboardPemilikController::class, 'exportExcel'])->name('pemilik.dashboard.exportExcel');

    // Index
    Route::get('/obat', [ObatController::class, 'index'])->name('daftarobat.index');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('laporanpenjualan.index');
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('laporanpembelian.index');
    Route::get('/pelanggan', [PelangganManageController::class, 'index'])->name('daftarpelanggan.index');
    Route::get('/distributor', [DistributorController::class, 'index'])->name('daftardistributor.index');
    Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('daftarpengiriman.index');

    // Detail
    Route::get('/detail-obat', [ObatController::class, 'show'])->name('daftarobat.show');
    Route::get('/detail-penjualan', [PenjualanController::class, 'show'])->name('laporanpenjualan.show');
    Route::get('/detail-pembelian', [PembelianController::class, 'show'])->name('laporanpembelian.show');
    Route::get('/detail-distributor', [DistributorController::class, 'show'])->name('daftardistributor.show');
    Route::get('/detail-pengiriman', [PengirimanController::class, 'show'])->name('daftarpengiriman.show');
});

Route::prefix('apoteker')->middleware(['auth', CheckRoleUser::class . ':apoteker'])->group(function () {
    Route::get('/', [App\Http\Controllers\ApotekerController::class, 'index'])->name('apoteker.dashboard');
});

Route::prefix('karyawan')->middleware(['auth', CheckRoleUser::class . ':karyawan'])->group(function () {
    Route::get('/', [App\Http\Controllers\KaryawanController::class, 'index'])->name('karyawan.dashboard');
});

Route::prefix('kasir')->middleware(['auth', CheckRoleUser::class . ':kasir'])->group(function () {
    Route::get('/', [App\Http\Controllers\KasirController::class, 'index'])->name('kasir.dashboard');
    Route::get('/dashboard', [DashboardKasirController::class, 'index'])->name('kasir.dashboard');
    Route::get('/obat', [ObatController::class, 'index'])->name('daftarobat.index');
});

// ==================== Profile Pelanggan ====================
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('fe.profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('fe.profile.update');
});

// ==================== Profile User ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/user-profile', [ProfileUserController::class, 'index'])->name('be.profile');
    Route::put('/user-profile', [ProfileUserController::class, 'update'])->name('profile.update');
});

// ==================== Resource CRUD ====================

// User Management
Route::middleware(['auth', CheckRoleUser::class . ':admin'])->group(function () {
    Route::resource('user', UsersController::class)->names([
        'index' => 'user.manage',
        'create' => 'user.create',
        'edit' => 'user.edit',
        'destroy' => 'user.destroy',
        'store' => 'user.store',
        'update' => 'user.update',
    ]);
});

Route::middleware(['auth', CheckRoleUser::class . ':admin,pemilik'])->group(function () {
    Route::resource('pelanggan', PelangganManageController::class)->names([
        'index' => 'pelanggan.manage',
        'create' => 'pelanggan.create',
        'edit' => 'pelanggan.edit',
        'destroy' => 'pelanggan.destroy',
        'store' => 'pelanggan.store',
        'update' => 'pelanggan.update',
    ]);
});

// Obat Management 
Route::middleware(['auth', CheckRoleUser::class . ':apoteker,admin,pemilik'])->group(function () {
    Route::resource('obat', ObatController::class)->names([
        'index' => 'obat.manage',
        'create' => 'obat.create',
        'edit' => 'obat.edit',
        'destroy' => 'obat.destroy',
        'store' => 'obat.store',
        'update' => 'obat.update',
    ]);
});

// Penjualan Management
Route::middleware(['auth', CheckRoleUser::class . ':kasir,admin,pemilik'])->group(function () {
    Route::resource('penjualan', PenjualanController::class)->names([
        'index' => 'penjualan.manage',
        'create' => 'penjualan.create',
        'store' => 'penjualan.store',
        'show' => 'penjualan.show',
        'edit' => 'penjualan.edit',
        'update' => 'penjualan.update',
        'destroy' => 'penjualan.destroy',
    ]);

    Route::post('/penjualan/upload-resep', [PenjualanController::class, 'uploadResep'])->name('penjualan.upload-resep');
    Route::delete('/penjualan/delete-resep', [PenjualanController::class, 'deleteResep'])->name('penjualan.delete-resep');
});

// Distributor Management
Route::middleware(['auth', CheckRoleUser::class . ':admin,pemilik'])->group(function () {
    Route::resource('distributor', App\Http\Controllers\DistributorController::class)->names([
        'index' => 'distributor.index',
        'create' => 'distributor.create',
        'store' => 'distributor.store',
        'show' => 'distributor.show',
        'edit' => 'distributor.edit',
        'update' => 'distributor.update',
        'destroy' => 'distributor.destroy',
    ]);
    

    Route::resource('detail-penjualan', App\Http\Controllers\DetailPenjualanController::class);
});

// Pembelian & Jenis Obat Management
Route::middleware(['auth', CheckRoleUser::class . ':apoteker,admin,pemilik'])->group(function () {
    Route::resource('pembelian', PembelianController::class)->names([
        'index' => 'pembelian.manage',
        'create' => 'pembelian.create',
        'store' => 'pembelian.store',
        'show' => 'pembelian.show',
        'edit' => 'pembelian.edit',
        'update' => 'pembelian.update',
        'destroy' => 'pembelian.destroy',
    ]);

    Route::resource('jenis-obat', JenisObatController::class)->names([
        'index' => 'jenis-obat.manage',
        'create' => 'jenis-obat.create',
        'store' => 'jenis-obat.store',
        'show' => 'jenis-obat.show',
        'edit' => 'jenis-obat.edit',
        'update' => 'jenis-obat.update',
        'destroy' => 'jenis-obat.destroy',
    ]);
    
    Route::resource('detail-pembelian', App\Http\Controllers\DetailPembelianController::class);
});

// Pengiriman Management
Route::middleware(['auth', CheckRoleUser::class . ':karyawan,admin,pemilik'])->group(function () {
    Route::resource('pengiriman', PengirimanController::class)->names([
        'index' => 'pengiriman.manage',
        'create' => 'pengiriman.create',
        'store' => 'pengiriman.store',
        'show' => 'pengiriman.show',
        'edit' => 'pengiriman.edit',
        'update' => 'pengiriman.update',
        'destroy' => 'pengiriman.destroy',
    ]);

    Route::resource('jenis-pengiriman', JenisPengirimanController::class)->names([
        'index' => 'jenis-pengiriman.manage',
        'create' => 'jenis-pengiriman.create',
        'store' => 'jenis-pengiriman.store',
        'show' => 'jenis-pengiriman.show',
        'edit' => 'jenis-pengiriman.edit',
        'update' => 'jenis-pengiriman.update',
        'destroy' => 'jenis-pengiriman.destroy',
    ]);

    Route::resource('detail-pengiriman', App\Http\Controllers\DetailPengirimanController::class);
}); 

// Metode Bayar
Route::middleware(['auth', CheckRoleUser::class . ':admin,kasir'])->group(function () {
    Route::resource('metode-bayar', App\Http\Controllers\MetodeBayarController::class);
});


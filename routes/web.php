<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\Admin\KontakController as AdminKontakController;
use App\Http\Controllers\Admin\KecamatanController as AdminKecamatanController;
use App\Http\Controllers\Admin\WhatsAppController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| File ini mengatur semua route utama untuk user dan admin.
|--------------------------------------------------------------------------
*/

// ===============================
// Halaman User (Umum)
// ===============================

// Halaman kontak user
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');

// FAQ User (versi UserController)
Route::get('/faq', [UserController::class, 'faq'])->name('user.faq');

// ==============================
// Tes Zenziva Manual (optional, untuk debugging)
// ==============================
Route::post('/test-zenziva/send', function (Request $request) {
    $phone = $request->input('phone');
    $msg = $request->input('message', 'Test SMS dari Zenziva');

    if (!preg_match('/^[0-9]{9,15}$/', $phone)) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Format nomor tidak valid (angka 9–15 digit).'
        ], 422);
    }

    $endpoint = config('services.sms.zenziva_endpoint_reguler'); // atau masking
    $response = Http::asForm()->post($endpoint, [
        'userkey' => config('services.sms.zenziva_userkey'),
        'passkey' => config('services.sms.zenziva_passkey'),
        'nohp'    => $phone,
        'pesan'   => $msg,
    ]);

    Log::info('Zenziva raw response: '.$response->body());

    // Coba parse XML (karena Zenziva sering kirim XML)
    $parsed = null;
    try {
        $xml = simplexml_load_string($response->body());
        if ($xml && isset($xml->message)) {
            $status = (string) ($xml->message->status ?? $xml->status ?? '');
            $text   = (string) ($xml->message->text ?? $xml->text ?? '');
            $parsed = ['status' => $status, 'text' => $text];
            Log::info('Zenziva parsed: '.json_encode($parsed));
        }
    } catch (\Exception $e) {
        Log::warning('Failed parsing Zenziva XML: '.$e->getMessage());
    }

    return response()->json([
        'ok'     => $response->successful(),
        'status' => $response->status(),
        'body'   => $response->body(),
        'parsed' => $parsed,
    ]);
});

// ==============================
// 📘 Pelaporan (User)
// ==============================
Route::get('/pelaporan', [LaporanController::class, 'create']);
Route::post('/pelaporan/request-otp', [OtpController::class, 'requestOtp']);
Route::post('/pelaporan/verify-otp', [OtpController::class, 'verifyOtp']);
Route::post('/pelaporan', [LaporanController::class, 'storeFallback']); // fallback opsional

// ===============================
// Halaman Utama (User)
// ===============================
Route::get('/', [SectionController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/data', [HomeController::class, 'data'])->name('data');
Route::get('/maps', [HomeController::class, 'maps'])->name('maps');
Route::get('/sistemcerdas', [HomeController::class, 'sistemcerdas'])->name('sistemcerdas');

// FAQ User (versi HomeController, dengan nama 'faq')
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// API publik untuk ambil data titik banjir
Route::get('/api/maps', function () {
    return \App\Models\Map::all();
});

// Duplikat (seperti file awalmu) – route FAQ user
Route::get('/faq', [UserController::class, 'faq'])->name('user.faq');


// ==============================
// Login / Logout Admin
// ==============================
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


// =====================================================================
// Semua route ADMIN di bawah ini diproteksi middleware 'admin.auth'
// =====================================================================
Route::middleware('admin.auth')->group(function () {

    // ==============================
    // 📘 Admin (Laporan)
    // ==============================
    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::delete('/admin/laporan/{id}', [LaporanController::class, 'destroy']);

    // ==============================
    // Admin Kontak (dari file awalmu)
    // ==============================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('kontak', AdminKontakController::class)->parameters(['kontak' => 'kontak']);
    });

    // ==============================
    // Admin FAQ (dari file awalmu)
    // ==============================
    Route::prefix('admin')->group(function () {
        Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
        Route::post('/faq/category', [FaqController::class, 'storeCategory'])->name('faq.category.store');
        Route::post('/faq/store', [FaqController::class, 'storeFaq'])->name('faq.store');
        Route::get('/faq/{id}/edit', [FaqController::class, 'edit'])->name('faq.edit');
        Route::put('/faq/{id}', [FaqController::class, 'updateFaq'])->name('faq.update');
        Route::put('/faq/category/{id}', [FaqController::class, 'updateCategory'])->name('faq.category.update');
        Route::delete('/faq/{id}', [FaqController::class, 'destroyFaq'])->name('faq.destroy');
        Route::delete('/faq/category/{id}', [FaqController::class, 'destroyCategory'])->name('faq.category.destroy');
    });

    // ===============================
    // Admin Dashboard & CRUD utama
    // ===============================
    Route::prefix('admin')->group(function () {
        // Dashboard utama admin
        Route::get('/', [AdminController::class, 'index'])->name('admin.home');

        // CRUD Slide
        Route::post('/slides', [AdminController::class, 'storeSlide'])->name('slides.store');
        Route::put('/slides/{slide}', [AdminController::class, 'updateSlide'])->name('slides.update');
        Route::delete('/slides/{slide}', [AdminController::class, 'destroySlide'])->name('slides.destroy');

        // CRUD Section
        Route::get('/sections', [SectionController::class, 'admin'])->name('admin.sections');
        Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
        Route::put('/sections/{id}', [SectionController::class, 'update'])->name('sections.update');
        Route::delete('/sections/{id}', [SectionController::class, 'destroy'])->name('sections.destroy');

        // Admin WebsiteKontak
        Route::resource('websitekontak', \App\Http\Controllers\Admin\WebsiteKontakController::class)->names([
            'index'   => 'admin.websitekontak.index',
            'create'  => 'admin.websitekontak.create',
            'store'   => 'admin.websitekontak.store',
            'show'    => 'admin.websitekontak.show',
            'edit'    => 'admin.websitekontak.edit',
            'update'  => 'admin.websitekontak.update',
            'destroy' => 'admin.websitekontak.destroy',
        ]);

        // Admin News
        Route::resource('news', \App\Http\Controllers\Admin\NewsController::class)->names([
            'index'   => 'admin.news.index',
            'create'  => 'admin.news.create',
            'store'   => 'admin.news.store',
            'show'    => 'admin.news.show',
            'edit'    => 'admin.news.edit',
            'update'  => 'admin.news.update',
            'destroy' => 'admin.news.destroy',
        ]);

        // Admin Monthly Stats
        Route::resource('monthly-stats', \App\Http\Controllers\Admin\MonthlyStatController::class)->names([
            'index'   => 'admin.monthly-stats.index',
            'create'  => 'admin.monthly-stats.create',
            'store'   => 'admin.monthly-stats.store',
            'show'    => 'admin.monthly-stats.show',
            'edit'    => 'admin.monthly-stats.edit',
            'update'  => 'admin.monthly-stats.update',
            'destroy' => 'admin.monthly-stats.destroy',
        ]);

        // Admin Kecamatan
        Route::resource('kecamatans', AdminKecamatanController::class)->names([
            'index'   => 'admin.kecamatans.index',
            'create'  => 'admin.kecamatans.create',
            'store'   => 'admin.kecamatans.store',
            'show'    => 'admin.kecamatans.show',
            'edit'    => 'admin.kecamatans.edit',
            'update'  => 'admin.kecamatans.update',
            'destroy' => 'admin.kecamatans.destroy',
        ]);

        // Admin WhatsApp
        Route::resource('whatsapp', WhatsAppController::class)->names([
            'index'   => 'admin.whatsapp.index',
            'create'  => 'admin.whatsapp.create',
            'store'   => 'admin.whatsapp.store',
            'edit'    => 'admin.whatsapp.edit',
            'update'  => 'admin.whatsapp.update',
            'destroy' => 'admin.whatsapp.destroy',
        ])->except(['show']);

        // CRUD Akun Admin (tambah baru sesuai permintaanmu)
        Route::resource('accounts', AdminAccountController::class)->names([
            'index'   => 'admin.accounts.index',
            'create'  => 'admin.accounts.create',
            'store'   => 'admin.accounts.store',
            'show'    => 'admin.accounts.show',   // kalau tidak dipakai bisa diabaikan
            'edit'    => 'admin.accounts.edit',
            'update'  => 'admin.accounts.update',
            'destroy' => 'admin.accounts.destroy',
        ])->parameters([
            'accounts' => 'account',
        ]);
    });

    // ==============================
    // maps admin (dari file awalmu)
    // ==============================
    Route::prefix('admin')->group(function () {
        Route::get('/maps', [MapController::class, 'index'])->name('admin.maps');
        Route::post('/maps', [MapController::class, 'store'])->name('maps.store');
        Route::put('/maps/{id}', [MapController::class, 'update'])->name('maps.update');
        Route::delete('/maps/{id}', [MapController::class, 'destroy'])->name('maps.destroy');
    });
});

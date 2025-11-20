<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SectionController;


use App\Http\Controllers\MapController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UserController;



use App\Http\Controllers\KontakController;
use App\Http\Controllers\Admin\KontakController as AdminKontakController;
use App\Http\Controllers\Admin\KecamatanController as AdminKecamatanController;

use App\Http\Controllers\Admin\WhatsAppController;





Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');

// Admin group (sesuaikan prefix/middleware jika berbeda di projectmu)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('kontak', AdminKontakController::class)->parameters(['kontak' => 'kontak']);
});




// ==============================
// FAQ User
// ==============================
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

// ==============================
// 📘 Admin (Laporan)
// ==============================
Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
Route::delete('/admin/laporan/{id}', [LaporanController::class, 'destroy']);

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


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| File ini mengatur semua route utama untuk user dan admin.
|--------------------------------------------------------------------------
*/

// ===============================
// Halaman Utama (User)
// ===============================
Route::get('/', [SectionController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/data', [HomeController::class, 'data'])->name('data');
Route::get('/maps', [HomeController::class, 'maps'])->name('maps');
Route::get('/sistemcerdas', [HomeController::class, 'sistemcerdas'])->name('sistemcerdas');

Route::get('/faq', [HomeController::class, 'faq'])->name('faq');


// ===============================
// Admin Dashboard & Slide CRUD
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

    Route::resource('websitekontak', \App\Http\Controllers\Admin\WebsiteKontakController::class)->names([
        'index'   => 'admin.websitekontak.index',
        'create'  => 'admin.websitekontak.create',
        'store'   => 'admin.websitekontak.store',
        'show'    => 'admin.websitekontak.show',
        'edit'    => 'admin.websitekontak.edit',
        'update'  => 'admin.websitekontak.update',
        'destroy' => 'admin.websitekontak.destroy',
    ]);
    // Admin News CRUD
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class)->names([
        'index'   => 'admin.news.index',
        'create'  => 'admin.news.create',
        'store'   => 'admin.news.store',
        'show'    => 'admin.news.show',
        'edit'    => 'admin.news.edit',
        'update'  => 'admin.news.update',
        'destroy' => 'admin.news.destroy',
    ]);

    Route::resource('monthly-stats', \App\Http\Controllers\Admin\MonthlyStatController::class)->names([
        'index'   => 'admin.monthly-stats.index',
        'create'  => 'admin.monthly-stats.create',
        'store'   => 'admin.monthly-stats.store',
        'show'    => 'admin.monthly-stats.show',
        'edit'    => 'admin.monthly-stats.edit',
        'update'  => 'admin.monthly-stats.update',
        'destroy' => 'admin.monthly-stats.destroy',
    ]);

    Route::resource('kecamatans', \App\Http\Controllers\Admin\KecamatanController::class)->names([
        'index'   => 'admin.kecamatans.index',
        'create'  => 'admin.kecamatans.create',
        'store'   => 'admin.kecamatans.store',
        'show'    => 'admin.kecamatans.show',
        'edit'    => 'admin.kecamatans.edit',
        'update'  => 'admin.kecamatans.update',
        'destroy' => 'admin.kecamatans.destroy',
    ]);

    Route::resource('whatsapp', WhatsAppController::class)->names([
    'index'   => 'admin.whatsapp.index',
    'create'  => 'admin.whatsapp.create',
    'store'   => 'admin.whatsapp.store',
    'edit'    => 'admin.whatsapp.edit',
    'update'  => 'admin.whatsapp.update',
    'destroy' => 'admin.whatsapp.destroy',
    ])->except(['show']);

});




//maps


Route::prefix('admin')->group(function () {
    Route::get('/maps', [MapController::class, 'index'])->name('admin.maps');
    Route::post('/maps', [MapController::class, 'store'])->name('maps.store');
    Route::put('/maps/{id}', [MapController::class, 'update'])->name('maps.update');
    Route::delete('/maps/{id}', [MapController::class, 'destroy'])->name('maps.destroy');
});


// API publik untuk ambil data titik banjir
Route::get('/api/maps', function () {
    return \App\Models\Map::all();
});


Route::get('/faq', [UserController::class, 'faq'])->name('user.faq');




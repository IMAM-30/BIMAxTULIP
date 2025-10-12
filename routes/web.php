<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SectionController;


use App\Http\Controllers\MapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| File ini mengatur semua route utama untuk user dan admin.
|--------------------------------------------------------------------------
*/

// ===============================
// 🔹 Halaman Utama (User)
// ===============================
Route::get('/', [SectionController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/data', fn() => view('User.data'))->name('data');
Route::get('/maps', fn() => view('User.maps'))->name('maps');
Route::get('/pelaporan', fn() => view('User.pelaporan'))->name('pelaporan');
Route::get('/faq', fn() => view('User.faq'))->name('faq');
Route::get('/kontak', fn() => view('User.kontak'))->name('kontak');

// ===============================
// 🔹 Admin Dashboard & Slide CRUD
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



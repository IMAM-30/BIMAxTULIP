<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SectionController;


use App\Http\Controllers\MapController;
use App\Http\Controllers\Admin\FaqController;

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
// 🔹 Halaman Utama (User)
// ===============================
Route::get('/', [SectionController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/data', [HomeController::class, 'data'])->name('data');
Route::get('/maps', [HomeController::class, 'maps'])->name('maps');
Route::get('/pelaporan', [HomeController::class, 'pelaporan'])->name('pelaporan');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

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


Route::get('/faq', [UserController::class, 'faq'])->name('user.faq');

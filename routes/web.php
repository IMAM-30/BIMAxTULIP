<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Admin dashboard & slide CRUD
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin-home');
    Route::post('/slides', [AdminController::class, 'storeSlide'])->name('slides.store');
    Route::put('/slides/{slide}', [AdminController::class, 'updateSlide'])->name('slides.update');
    Route::delete('/slides/{slide}', [AdminController::class, 'destroySlide'])->name('slides.destroy');
});


// Halaman user
Route::get('/', function () {
    return view('welcome');
});
//Route::get('/home', function () {
//    return view('User.home');
//});
Route::get('/data', function () {
    return view('User.data');
});
Route::get('/maps', function () {
    return view('User.maps');
});
Route::get('/pelaporan', function () {
    return view('User.pelaporan');
});
Route::get('/faq', function () {
    return view('User.faq');
});
Route::get('/kontak', function () {
    return view('User.kontak');
});

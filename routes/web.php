<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {


    Route::get('/demo', function () {
    return view('image.demo');
});
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/images/index', [UploadController::class, 'index'])->name('images.index'); // fetch images
Route::post('/images/upload', [UploadController::class, 'upload'])->name('images.upload'); // ajax upload

Route::get('/images/view', [UploadController::class, 'view'])->name('images.view');

    Route::resource('imageses', ImageController::class);

    
});

require __DIR__.'/auth.php';
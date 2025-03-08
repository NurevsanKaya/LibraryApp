<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/about', function () {
    return view('aboutus');
});
Route::get('/information', function () {
    return view('information');
});
Route::get('/mission', function () {
    return view('mission');
});
Route::get('/direction', function () {
    return view('direction');
});
Route::get('/hours', function () {
    return view('hours');
});
Route::get('/services', function () {
    return view('services');
});

Route::get('/myaccount', function () {
    return view('myaccount');
});
Route::get('/contact', function () {
    return view('contact');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

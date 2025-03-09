<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

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
Route::delete('/Admin/{id}', [AdminController::class, 'destroy'])->name('Book.destroy');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Books Management
    Route::get('/books', [AdminController::class, 'books'])->name('admin.books.index');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('admin.books.store');
    Route::put('/books/{id}', [AdminController::class, 'updateBook'])->name('admin.books.update');
    Route::get('/books/{id}', [AdminController::class, 'getBook'])->name('admin.books.get');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

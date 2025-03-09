<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StockController;
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



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Rotaları
Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Kitap
    Route::get('/books', [AdminController::class, 'books'])->name('admin.books.index');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('admin.books.store');
    Route::get('/books/search', [StockController::class, 'searchBook'])->name('admin.books.search');
    Route::put('/books/{id}', [AdminController::class, 'updateBook'])->name('admin.books.update');
    Route::get('/books/{id}', [AdminController::class, 'getBook'])->name('admin.books.get');
    Route::delete('/Admin/{id}', [AdminController::class, 'destroy'])->name('Book.destroy');


    // Stok
    Route::get('/stocks', [StockController::class, 'index'])->name('admin.stocks.index');
    Route::post('/stocks', [StockController::class, 'store'])->name('admin.stocks.store');
    Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('admin.stocks.show');
    Route::put('/stocks/{stock}', [StockController::class, 'update'])->name('admin.stocks.update');
    Route::delete('/stocks/{stock}', [StockController::class, 'destroy'])->name('admin.stocks.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

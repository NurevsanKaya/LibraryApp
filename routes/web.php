<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StockController;
use App\Models\AcquisitionSource;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\Admin\UserController;

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
Route::get('/search', [BookController::class, 'search'])->name('search');


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
    Route::delete('/books/{id}', [AdminController::class, 'destroy'])->name('Book.destroy');


    // Stok
    Route::get('/stocks', [StockController::class, 'index'])->name('admin.stocks.index');
    Route::post('/stocks', [StockController::class, 'store'])->name('admin.stocks.store');
    Route::get('/stocks/available-shelves', [StockController::class, 'getAvailableShelves'])->name('admin.stocks.shelves');
    Route::get('/stocks/{stock}', [StockController::class, 'show'])->name('admin.stocks.show');
    Route::put('/stocks/{stock}', [StockController::class, 'update'])->name('admin.stocks.update');
    Route::delete('/stocks/{stock}', [StockController::class, 'destroy'])->name('admin.stocks.destroy');

    // Metadata Yönetimi (Yayınevi, Kategori, Tür, Yazar)

    Route::get('/data-adding', [AdminController::class, 'dataAdding'])->name('admin.data.adding');


    // Yayınevi
    Route::post('/publishers', [PublisherController::class, 'store'])->name('admin.publishers.store');

    // Kategori
    Route::post('/categories', [CategoriesController::class, 'store'])->name('admin.categories.store');

    // Tür
    Route::post('/genres', [GenresController::class, 'store'])->name('admin.genres.store');

    // Yazar
    Route::post('/authors', [AuthorController::class, 'store'])->name('admin.authors.store');

    Route::post('/acquisition_source', [AcquisitionSource::class, 'store'])->name('admin.acquisition-sources.store');


    // Kullanıcı Yönetimi
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserborrowController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserOldBorrowController;
use App\Http\Controllers\UserPenaltyController;
use App\Http\Controllers\Admin\BookReportController;

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\AcquisitionSourceController;

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


Route::get('/dashboard', [UserDashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Admin Rotaları
Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Kitap Raporlama
    Route::get('/book-reports', [BookReportController::class, 'index'])->name('admin.book-reports.index');
    Route::get('/book-reports/results', [BookReportController::class, 'getResults'])->name('admin.book-reports.results');
    Route::get('/book-reports/quick-filter/{filterType}', [BookReportController::class, 'getQuickFilterResults'])->name('admin.book-reports.quick-filter');

    // Kitap
    Route::get('/books', [AdminController::class, 'books'])->name('admin.books.index');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('admin.books.store');
    Route::get('/books/stock-search', [StockController::class, 'searchBook'])->name('admin.books.stock.search');
    Route::get('/books/search', [BookController::class, 'search'])->name('admin.books.search');
    Route::get('/books/borrowing-search', [BorrowingController::class, 'borrowingSearch'])->name('admin.books.borrowing-search');
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

    // Edinme Kaynağı
    Route::post('/acquisition-sources', [AcquisitionSourceController::class, 'store'])->name('admin.acquisition-sources.store');

    // Kullanıcı Yönetimi
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('admin.users.show');

    // Kitap Ödünç İşlemleri
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('admin.borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('admin.borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('admin.borrowings.store');
   Route::get('/borrowings/search-users', [BorrowingController::class, 'searchUsers'])->name('admin.borrowings.search.users');
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show'])->name('admin.borrowings.show');
    Route::post('/borrowings/{id}/return', [BorrowingController::class, 'returnBook'])->name('admin.borrowings.return');
    Route::post('/borrowings/{id}/extend', [BorrowingController::class, 'extendDueDate'])->name('admin.borrowings.extend');
//ceza işlemleri

    Route::get('/payments', [\App\Http\Controllers\PenaltyPaymentController::class, 'index'])->name('admin.payments.index');
    Route::post('/penalties/{id}/approve', [\App\Http\Controllers\PenaltyPaymentController::class, 'approve'])->name('admin.penalty.approve');
    Route::post('/penalties/{id}/reject', [\App\Http\Controllers\PenaltyPaymentController::class, 'reject'])->name('admin.penalty.reject');
    
    // Ceza Ayarları
    Route::get('/penalty-settings', [\App\Http\Controllers\PenaltySettingController::class, 'index'])->name('admin.penalty.settings');
    Route::post('/penalty-settings', [\App\Http\Controllers\PenaltySettingController::class, 'update'])->name('admin.penalty.settings.update');
});

Route::middleware('auth')->group(function () {

    // Kullanıcı Kitap Ödünçleri (UserBorrowController)
    Route::get('/my-borrowings', [UserBorrowController::class, 'index'])->name('user.borrowings');

    // Kullanıcı Cezaları (UserPenaltyController)
    Route::get('/my-penalties', [UserPenaltyController::class, 'index'])->name('user.penalties');


    Route::post('/penalties/{id}/upload', [UserPenaltyController::class, 'uploadReceipt'])->name('penalty.pay');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //geçmiş ödünçler
    Route::get('/my-borrowings-old', [UserOldBorrowController::class, 'index'])->name('user.oldborrowings');

});

require __DIR__.'/auth.php';

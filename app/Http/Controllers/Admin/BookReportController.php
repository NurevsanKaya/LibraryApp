<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Stock;
use App\Models\Categories;
use App\Models\Publisher;
use App\Models\AcquisitionSource;
use App\Models\Author;
use App\Models\User;
use App\Models\Location;
use App\Models\Bookshelf;
use App\Models\Shelf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookReportController extends Controller
{
    public function index()
    {
        // İstatistik kartları için verileri hazırla
        $totalBooks = Book::count();
        $borrowedBooks = Borrowing::whereNull('return_date')->where('status', 'active')->count();
        $dueTodayBooks = Borrowing::whereNull('return_date')
            ->whereDate('due_date', Carbon::today())
            ->count();

        // Filtreler için gerekli verileri hazırla
        $categories = Categories::all();
        $publishers = Publisher::all();
        $acquisitionSources = AcquisitionSource::all();
        $authors = Author::all();
        $locations = Location::all();
        $bookshelves = Bookshelf::with('location')->get();
        $shelves = Shelf::with('bookshelf')->get();

        return view('admin.book-reports.index', compact(
            'totalBooks',
            'borrowedBooks',
            'dueTodayBooks',
            'categories',
            'publishers',
            'acquisitionSources',
            'authors',
            'locations',
            'bookshelves',
            'shelves'
        ));
    }

    public function getResults(Request $request)
    {
        $query = Book::query()
            ->select([
                'books.*',
                'stocks.id as stock_id',
                'stocks.barcode',
                'stocks.status'
            ])
            ->leftJoin('stocks', 'books.id', '=', 'stocks.book_id')
            ->with(['category', 'publisher', 'authors', 'stocks.borrowings.user']);

        // Temel filtreler
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('isbn')) {
            $query->where('isbn', 'like', '%' . $request->isbn . '%');
        }
        if ($request->filled('author_id')) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('authors.id', $request->author_id);
            });
        }
        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }
        if ($request->filled('publication_year')) {
            $query->where('publication_year', $request->publication_year);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lokasyon ve Raf Filtreleri
        if ($request->filled('location')) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->whereHas('shelf.bookshelf.location', function ($q) use ($request) {
                    $q->where('id', $request->location);
                });
            });
        }
        if ($request->filled('bookcase')) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->whereHas('shelf.bookshelf', function ($q) use ($request) {
                    $q->where('id', $request->bookcase);
                });
            });
        }
        if ($request->filled('shelf')) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->where('shelf_id', $request->shelf);
            });
        }

        // Durum filtreleri
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'available':
                    $query->whereHas('stocks', function ($q) {
                        $q->where('status', 'available');
                    });
                    break;
                case 'borrowed':
                    $query->whereHas('stocks', function ($q) {
                        $q->where('status', 'borrowed');
                    });
                    break;
                case 'overdue':
                    $query->whereHas('stocks.borrowings', function ($q) {
                        $q->whereNull('return_date')
                        ->where('borrowings.return_date', null)  // İade edilmemiş olanlar
                        ->where('borrowings.due_date', '<', Carbon::now())  // Süresi geçmiş olanlar
                        ->where('borrowings.status', 'active') ; // Aktif ödünç işlemleri
                    });
                    break;
                
            }
        }

        // Tarih bazlı filtreler
        if ($request->filled('borrow_date_start')) {
            $query->whereHas('stocks.borrowings', function ($q) use ($request) {
                $q->whereDate('borrow_date', '>=', $request->borrow_date_start);
            });
        }
        if ($request->filled('borrow_date_end')) {
            $query->whereHas('stocks.borrowings', function ($q) use ($request) {
                $q->whereDate('borrow_date', '<=', $request->borrow_date_end);
            });
        }
        if ($request->filled('return_date_start')) {
            $query->whereHas('stocks.borrowings', function ($q) use ($request) {
                $q->whereDate('return_date', '>=', $request->return_date_start);
            });
        }
        if ($request->filled('return_date_end')) {
            $query->whereHas('stocks.borrowings', function ($q) use ($request) {
                $q->whereDate('return_date', '<=', $request->return_date_end);
            });
        }
        if ($request->filled('acquisition_date_start')) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->whereDate('acquisition_date', '>=', $request->acquisition_date_start);
            });
        }
        if ($request->filled('acquisition_date_end')) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->whereDate('acquisition_date', '<=', $request->acquisition_date_end);
            });
        }

        // Edinme kaynağı filtreleri
        if ($request->filled('acquisition_source_id')) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->where('acquisition_source_id', $request->acquisition_source_id);
            });
        }

        // Fiyat/maliyet filtreleri
        if ($request->filled('min_price')) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->where('acquisition_price', '>=', $request->min_price);
            });
        }
        if ($request->filled('max_price')) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->where('acquisition_price', '<=', $request->max_price);
            });
        }

        // Üye filtreleri
        if ($request->filled('user_id')) {
            $query->whereHas('stocks.borrowings.user', function ($q) use ($request) {
                $q->where('users.id', $request->user_id);
            });
        }
        if ($request->filled('user_status')) {
            $query->whereHas('stocks.borrowings.user', function ($q) use ($request) {
                $q->where('is_active', $request->user_status === 'active');
            });
        }

        $books = $query->get();
        
        // Sorgu tipini belirle
        $queryType = 'search'; // Normal arama için 'search' olarak ayarla

        return view('admin.book-reports.partials._results', compact('books', 'queryType'));
    }

    // Hızlı filtre butonları için ana metot
    public function getQuickFilterResults(Request $request, string $filterType)
    {
        switch ($filterType) {
                case 'overdue':
                return $this->getOverdueBooks($request);
                case 'due_today':
                return $this->getDueTodayBooks($request);
                case 'most_borrowed':
                return $this->getMostBorrowedBooks($request);
                case 'added_last_month':
                return $this->getLastMonthBooks($request);
                case 'never_borrowed':
                return $this->getNeverBorrowedBooks($request);
                case 'available':
                return $this->getAvailableBooks($request);
                case 'active_borrowings':
                return $this->getActiveBorrowings($request);
            default:
                return response()->json(['error' => 'Geçersiz filtre tipi'], 400);
        }
    }

    // Geciken kitaplar için metot
    protected function getOverdueBooks(Request $request)
    {
        // Gecikmiş ödünç işlemlerini bulalım
        $overdueBooks = Book::query()  // Query Builder'ı başlat
            ->select([
                'books.*',
                'stocks.id as stock_id',  // Stok ID'sini de alalım
                'stocks.barcode',         // Barkod bilgisini ekledik
                'borrowings.due_date',    // Teslim tarihini göstermek için
                'borrowings.id as borrowing_id'  // Ödünç işlem ID'si
            ])
            ->join('stocks', 'books.id', '=', 'stocks.book_id')  // Stoklar ile birleştir
            ->join('borrowings', 'stocks.id', '=', 'borrowings.stock_id')  // Ödünç işlemleri ile birleştir
            ->with(['category', 'publisher', 'authors'])  // İlişkili verileri yükle
            ->where('borrowings.return_date', null)  // İade edilmemiş olanlar
            ->where('borrowings.due_date', '<', Carbon::now())  // Süresi geçmiş olanlar
            ->where('borrowings.status', 'active')  // Aktif ödünç işlemleri
            ->orderBy('books.name')  // Önce kitap adına göre sırala
            ->orderBy('borrowings.due_date');  // Sonra teslim tarihine göre sırala

        // Sonuçları getir
        $books = $overdueBooks->get();

        // Sorgu tipini belirle
        $queryType = 'overdue';

        return view('admin.book-reports.partials._results', compact('books', 'queryType'));
    }

    // Bugün iade edilecek kitaplar için metot
    protected function getDueTodayBooks(Request $request)
    {
        // Bugün iade edilecek kitapları bulalım
        $dueTodayBooks = Book::query()
            ->select([
                'books.*',
                'stocks.id as stock_id',
                'stocks.barcode',
                'borrowings.due_date',
                'borrowings.id as borrowing_id'
            ])
            ->join('stocks', 'books.id', '=', 'stocks.book_id')
            ->join('borrowings', 'stocks.id', '=', 'borrowings.stock_id')
            ->with(['category', 'publisher', 'authors'])
            ->where('borrowings.return_date', null)  // İade edilmemiş olanlar
            ->whereDate('borrowings.due_date', Carbon::today())  // Bugün iade edilecekler
            ->where('borrowings.status', 'active')  // Aktif ödünç işlemleri
            ->orderBy('books.name')
            ->orderBy('borrowings.due_date');

        // Sonuçları getir
        $books = $dueTodayBooks->get();

        // Sorgu tipini belirle
        $queryType = 'due_today';

        return view('admin.book-reports.partials._results', compact('books', 'queryType'));
    }

    // En çok ödünç alınan kitaplar için metot
    protected function getMostBorrowedBooks(Request $request)
    {
        // En çok ödünç alınan kitapları bulalım
        $mostBorrowedBooks = Book::query()
            ->select([
                'books.*',
                'stocks.id as stock_id',
                'stocks.barcode',
                DB::raw('COUNT(DISTINCT borrowings.id) as borrow_count'), // Toplam ödünç alma sayısı
                DB::raw('MAX(borrowings.borrow_date) as last_borrowed_date') // Son ödünç alma tarihi
            ])
            ->join('stocks', 'books.id', '=', 'stocks.book_id')
            ->join('borrowings', 'stocks.id', '=', 'borrowings.stock_id')
            ->with(['category', 'publisher', 'authors'])
            ->groupBy([
                'books.id',
                'books.name',
                'books.isbn',
                'books.publication_year',
                'books.category_id',
                'books.publisher_id',
                'books.created_at',
                'books.updated_at',
                'stocks.id',
                'stocks.barcode'
            ])
            ->orderByDesc('borrow_count')
            ->orderBy('books.name');

        // Sonuçları getir
        $books = $mostBorrowedBooks->get();
        
        // Sorgu tipini view'e gönder
        $queryType = 'most_borrowed';

        return view('admin.book-reports.partials._results', compact('books', 'queryType'));
    }

    // Son 1 ayda eklenen kitaplar için metot
    protected function getLastMonthBooks(Request $request)
    {
        // Son 1 ayda eklenen kitapları bulalım
        $lastMonthBooks = Book::query()
            ->select([
                'books.*',
                'stocks.id as stock_id',
                'stocks.barcode',
                'stocks.created_at as stock_created_at',
                'stocks.acquisition_price'
            ])
            ->join('stocks', 'books.id', '=', 'stocks.book_id')
            ->with(['category', 'publisher', 'authors'])
            ->where('stocks.created_at', '>=', Carbon::now()->subMonth())
            ->orderByDesc('stocks.created_at')
            ->orderBy('books.name');

        // Sonuçları getir
        $books = $lastMonthBooks->get();

        // Sorgu tipini belirle
        $queryType = 'last_month';

        return view('admin.book-reports.partials._results', compact('books', 'queryType'));
            }

    // Hiç ödünç alınmayan kitaplar için metot
    protected function getNeverBorrowedBooks(Request $request)
    {
        // Hiç ödünç alınmamış kitapları bulalım
        $neverBorrowedBooks = Book::query()
            ->select([
                'books.*',
                'stocks.id as stock_id',
                'stocks.barcode',
                'stocks.created_at as stock_created_at'
            ])
            ->join('stocks', 'books.id', '=', 'stocks.book_id')
            ->leftJoin('borrowings', 'stocks.id', '=', 'borrowings.stock_id')
            ->with(['category', 'publisher', 'authors'])
            ->whereNull('borrowings.id')
            ->orderBy('books.name');

        // Sonuçları getir
        $books = $neverBorrowedBooks->get();

        // Sorgu tipini belirle
        $queryType = 'never_borrowed';

        return view('admin.book-reports.partials._results', compact('books', 'queryType'));
    }

    // Rafta mevcut olan kitaplar için metot
    protected function getAvailableBooks(Request $request)
    {
        // Rafta mevcut olan kitapları bulalım
        $availableBooks = Book::query()
            ->select([
                'books.*',
                'stocks.id as stock_id',
                'stocks.barcode',
                'stocks.status'
            ])
            ->join('stocks', 'books.id', '=', 'stocks.book_id')
            ->with(['category', 'publisher', 'authors'])
            ->where('stocks.status', 'available')
            ->orderBy('books.name');

        // Sonuçları getir
        $books = $availableBooks->get();

        // Sorgu tipini belirle
        $queryType = 'available';

        return view('admin.book-reports.partials._results', compact('books', 'queryType'));
    }

    // Aktif ödünç işlemleri için metot
    protected function getActiveBorrowings(Request $request)
    {
        // Aktif ödünç işlemlerini bulalım
        $activeBorrowings = Book::query()
            ->select([
                'books.*',
                'stocks.id as stock_id',
                'stocks.barcode',
                'borrowings.borrow_date',
                'borrowings.due_date',
                'borrowings.id as borrowing_id'
            ])
            ->join('stocks', 'books.id', '=', 'stocks.book_id')
            ->join('borrowings', 'stocks.id', '=', 'borrowings.stock_id')
            ->with(['category', 'publisher', 'authors'])
            ->where('borrowings.return_date', null)
            ->where('borrowings.status', 'active')
            ->orderBy('borrowings.due_date')
            ->orderBy('books.name');

        // Sonuçları getir
        $books = $activeBorrowings->get();

        // Sorgu tipini belirle
        $queryType = 'active_borrowings';

        return view('admin.book-reports.partials._results', compact('books', 'queryType'));
    }
} 
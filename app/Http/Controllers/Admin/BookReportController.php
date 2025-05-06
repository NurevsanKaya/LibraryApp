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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookReportController extends Controller
{
    public function index()
    {
        // İstatistik kartları için verileri hazırla
        $totalBooks = Book::count();
        $borrowedBooks = Stock::where('status', 'borrowed')->count();
        $dueTodayBooks = Borrowing::whereNull('return_date')
            ->whereDate('due_date', Carbon::today())
            ->count();

        // Filtreler için gerekli verileri hazırla
        $categories = Categories::all();
        $publishers = Publisher::all();
        $acquisitionSources = AcquisitionSource::all();
        $authors = Author::all();

        return view('admin.book-reports.index', compact(
            'totalBooks',
            'borrowedBooks',
            'dueTodayBooks',
            'categories',
            'publishers',
            'acquisitionSources',
            'authors'
        ));
    }

    public function getResults(Request $request)
    {
        $query = Book::with(['category', 'publisher', 'authors', 'stocks.borrowings.user']);

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

        // Durum filtreleri
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'available':
                    $query->whereHas('stocks', function ($q) {
                        $q->where('status', 'active');
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
                            ->where('due_date', '<', Carbon::now());
                    });
                    break;
                case 'reserved':
                    $query->whereHas('stocks', function ($q) {
                        $q->where('status', 'reserved');
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

        $books = $query->paginate(10);

        return view('admin.book-reports.partials._results', compact('books'));
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

        
        // Sonuçları sayfalayarak getir
        $books = $overdueBooks->paginate(10);

        foreach($books as $book) {
            \Illuminate\Support\Facades\Log::info("Kitap: {$book->id} - {$book->name} - Barkod: {$book->barcode} - Teslim Tarihi: {$book->due_date}");
        }

        return view('admin.book-reports.partials._results', compact('books'));
    }

    // Bugün iade edilecek kitaplar için metot
    protected function getDueTodayBooks(Request $request)
    {
        return response()->json(['error' => 'Bu filtre şu anda aktif değil'], 400);
    }

    // En çok ödünç alınan kitaplar için metot
    protected function getMostBorrowedBooks(Request $request)
    {
        return response()->json(['error' => 'Bu filtre şu anda aktif değil'], 400);
    }

    // Son 1 ayda eklenen kitaplar için metot
    protected function getLastMonthBooks(Request $request)
    {
        return response()->json(['error' => 'Bu filtre şu anda aktif değil'], 400);
    }

    // Hiç ödünç alınmayan kitaplar için metot
    protected function getNeverBorrowedBooks(Request $request)
    {
        return response()->json(['error' => 'Bu filtre şu anda aktif değil'], 400);
    }

    // Rafta mevcut olan kitaplar için metot
    protected function getAvailableBooks(Request $request)
    {
        return response()->json(['error' => 'Bu filtre şu anda aktif değil'], 400);
    }

    // Aktif ödünç işlemleri için metot
    protected function getActiveBorrowings(Request $request)
    {
        return response()->json(['error' => 'Bu filtre şu anda aktif değil'], 400);
    }
} 
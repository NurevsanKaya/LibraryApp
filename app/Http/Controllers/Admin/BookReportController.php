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

        // Hızlı filtre butonları
        if ($request->filled('quick_filter')) {
            switch ($request->quick_filter) {
                case 'overdue':
                    $query->whereHas('stocks.borrowings', function ($q) {
                        $q->whereNull('return_date')
                            ->where('due_date', '<', Carbon::now());
                    });
                    break;
                case 'due_today':
                    $query->whereHas('stocks.borrowings', function ($q) {
                        $q->whereNull('return_date')
                            ->whereDate('due_date', Carbon::today());
                    });
                    break;
                case 'most_borrowed':
                    $query->withCount(['stocks.borrowings as borrow_count'])
                        ->orderByDesc('borrow_count');
                    break;
                case 'added_last_month':
                    $query->whereHas('stocks', function ($q) {
                        $q->whereDate('acquisition_date', '>=', Carbon::now()->subMonth());
                    });
                    break;
                case 'never_borrowed':
                    $query->whereDoesntHave('stocks.borrowings');
                    break;
                case 'available':
                    $query->whereHas('stocks', function ($q) {
                        $q->where('status', 'active');
                    });
                    break;
                case 'active_borrowings':
                    $query->whereHas('stocks.borrowings', function ($q) {
                        $q->whereNull('return_date');
                    });
                    break;
            }
        }

        $books = $query->paginate(10);

        return view('admin.book-reports.partials._results', compact('books'));
    }
} 
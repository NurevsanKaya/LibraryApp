<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Stock;
use App\Services\PenaltyService;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Tüm ödünç kayıtlarını listele
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'stock.book']);

        // Durum filtreleme
        if ($request->has('status') && $request->status) {
            if ($request->status == 'borrowed') {
                $query->whereNull('return_date');
            } elseif ($request->status == 'returned') {
                $query->whereNotNull('return_date');
            } elseif ($request->status == 'overdue') {
                $query->whereNull('return_date')
                    ->where('due_date', '<', Carbon::now()->format('Y-m-d'));
            }
        }

        // Arama filtresi
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('stock.book', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                })->orWhereHas('user', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // 📅 Tarih aralığı filtresi (borrow_date bazlı)
        if ($request->filled('start_date')) {
            $query->whereDate('borrow_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('borrow_date', '<=', $request->end_date);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.borrowings.index', compact('borrowings'));
    }
    public function create()
    {
        // Tüm kitapları getir
        $books = Book::with('stocks')->get();

        // Aktif kullanıcıları getir (üye rolündekileri)
        $users = User::where('role_id', 2)
                    ->where('is_active', 1)
                    ->get();

        // Şu anda ödünç verilmiş stokları bul (iade edilmeyenler)
        $borrowedStockIds = Borrowing::whereNull('return_date')
                            ->pluck('stock_id')
                            ->toArray();

        return view('admin.borrowings.create', compact('books', 'users', 'borrowedStockIds'));
    }

    /**
     * Yeni ödünç kaydı oluştur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'stock_ids' => 'required|array',
            'stock_ids.*' => 'nullable|exists:stocks,id',
            'borrow_dates' => 'required|array',
            'borrow_dates.*' => 'nullable|date',
            'due_dates' => 'required|array',
            'due_dates.*' => 'nullable|date',
            'borrow_durations' => 'required|array',
            'borrow_durations.*' => 'nullable|integer|min:1|max:365',
        ]);

        // Kullanıcının aktif olduğunu kontrol et
        $user = User::findOrFail($request->user_id);
        if (!$user->is_active) {
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu kullanıcı aktif değil. Kitap ödünç verilemez.');
        }

        // Kullanıcının kaç kitabı olduğunu kontrol et (opsiyonel limit kontrolü)
        $userBorrowingCount = Borrowing::where('user_id', $request->user_id)
                            ->whereNull('return_date')
                            ->count();

        // Toplam ödünç alınacak kitap sayısı
        $newBooksCount = count(array_filter($request->stock_ids));

        // Toplam kitap sayısı limiti kontrolü (örneğin en fazla 5)
        if ($userBorrowingCount + $newBooksCount > 5) { // Maksimum 5 kitap ödünç alabilir
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu kullanıcı maksimum ödünç alma limitine ulaşacak. En fazla ' . (5 - $userBorrowingCount) . ' kitap daha ödünç verebilirsiniz.');
        }

        // Stok kayıtlarının durumunu kontrol et
        $unavailableStocks = [];
        foreach ($request->stock_ids as $stockId) {
            if (empty($stockId)) continue;

            $stock = Stock::find($stockId);
            if (!$stock || $stock->status !== 'available') {
                $unavailableStocks[] = $stockId;
            }
        }

        if (!empty($unavailableStocks)) {
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Seçtiğiniz bazı kitap stokları artık müsait değil. Lütfen tekrar kontrol edin.');
        }

        // Her stok için ödünç kaydı oluştur
        $createdCount = 0;
        $errors = [];
        DB::beginTransaction();

        try {
            foreach ($request->stock_ids as $index => $stockId) {
                if (empty($stockId)) continue; // Seçilmemiş stok varsa atla

                try {
                    // Stok durumunu güncelle
                    $stock = Stock::findOrFail($stockId);
                    $stock->update(['status' => 'borrowed']);

                    // Ödünç kaydı oluştur
                    Borrowing::create([
                        'user_id' => $request->user_id,
                        'stock_id' => $stockId,
                        'borrow_date' => $request->borrow_dates[$index],
                        'due_date' => $request->due_dates[$index],
                        'status' => 'active', // Durumu active olarak ayarla
                    ]);

                    $createdCount++;
                } catch (\Exception $e) {
                    // Hata loglama
                    Log::error('Ödünç verme hatası: ' . $e->getMessage());
                    $errors[] = 'Kitap ID: ' . $stockId . ' - Hata: ' . $e->getMessage();
                }
            }

            if ($createdCount > 0) {
                DB::commit();
                // Başarı mesajını ödünç alınan kitap sayısına göre ayarla
                $successMessage = $createdCount == 1
                    ? 'Kitap başarıyla ödünç verildi.'
                    : $createdCount . ' adet kitap başarıyla ödünç verildi.';

                return redirect()
                    ->route('admin.borrowings.index')
                    ->with('success', $successMessage);
            } else {
                DB::rollBack();
                Log::error('Hiçbir kitap ödünç verilemedi - Hatalar: ' . implode(', ', $errors));
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Kitaplar ödünç verilemedi. Teknik bir sorun oluştu.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Genel ödünç verme hatası: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'İşlem sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Belirli bir ödünç kaydını görüntüle
     */
    public function show($id)
    {
        $borrowing = Borrowing::with(['user', 'stock.book'])->findOrFail($id);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    /**
     * Kitap iade işlemi
     */
    public function returnBook(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        // Eğer kitap zaten iade edildiyse hata döndür
        if ($borrowing->return_date !== null) {
            return redirect()->back()
                    ->with('error', 'Bu kitap zaten iade edilmiş.');
        }

        $request->validate([
            'return_date' => 'required|date',
        ]);

        // İade tarihini kaydet
        $borrowing->update([
            'return_date' => $request->return_date,
            'status' => 'completed',
        ]);

        // Stok durumunu güncelle - Stok tekrar kullanılabilir yapılıyor
        if ($borrowing->stock) {
            $borrowing->stock->update(['status' => 'available']);
        }

        // Gecikme kontrolü ve ceza işlemi
        $dueDate = Carbon::parse($borrowing->due_date);
        $returnDate = Carbon::parse($request->return_date);

        if ($returnDate->gt($dueDate)) {
            // Gecikme durumu - ceza oluştur
            $penaltyService = new PenaltyService();
            $penalty = $penaltyService->createOrUpdatePenalty($borrowing);
            
            if ($penalty) {
                return redirect()->route('admin.borrowings.index')
                        ->with('warning', 'Kitap gecikmeli olarak iade edildi. (' . $dueDate->diffInDays($returnDate) . ' gün gecikme). ' . 
                               $penalty->amount . ' TL ceza oluşturuldu.');
            } else {
                return redirect()->route('admin.borrowings.index')
                        ->with('warning', 'Kitap gecikmeli olarak iade edildi. (' . $dueDate->diffInDays($returnDate) . ' gün gecikme)');
            }
        }

        return redirect()->route('admin.borrowings.index')
                ->with('success', 'Kitap başarıyla iade alındı.');
    }

    /**
     * Kitap arama API endpoint'i - Stok bazında arama
     */
    public function searchBooks(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'books' => [],
                'borrowedBookIds' => [],
                'unavailableBookIds' => []
            ]);
        }

        // Kitapları ISBN veya ada göre ara
        $books = Book::with(['authors', 'stocks' => function($q) {
                $q->where('status', 'available');
            }])
            ->where(function($q) use ($query) {
                $q->where('isbn', 'like', "%{$query}%")
                  ->orWhere('name', 'like', "%{$query}%")
                  ->orWhereHas('authors', function($subq) use ($query) {
                      $subq->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%");
                  });
            })
            ->limit(10)
            ->get();

        // Her kitap için müsait stok sayısını ekle
        $books->each(function($book) {
            $book->available_stock = $book->stocks->count();
        });

        // Şu anda ödünç verilmiş kitapları bul (stock_id'leri üzerinden)
        $borrowedStockIds = Borrowing::whereNull('return_date')
                            ->pluck('stock_id')
                            ->toArray();

        // Stokta olmayan kitapları tespit et
        $unavailableBookIds = Book::whereDoesntHave('stocks', function($query) {
                $query->where('status', 'available');
            })->pluck('id')->toArray();

        return response()->json([
            'books' => $books,
            'borrowedBookIds' => $borrowedStockIds,
            'unavailableBookIds' => $unavailableBookIds
        ]);
    }

    /**
     * Kitap arama API endpoint'i - Ödünç vermeler için özel
     * Sadece barkod ile arama yapar
     */
    public function borrowingSearch(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'books' => [],
                'message' => 'Lütfen bir barkod girin',
                'success' => false
            ]);
        }

        // Sadece barkod ile stok araması yap
        $stocksByBarcode = Stock::with(['book.authors', 'shelf'])
            ->where('barcode', 'like', "%{$query}%")
            ->where('status', 'available')
            ->get();

        // Barkod ile eşleşme bulundu mu?
        if ($stocksByBarcode->isEmpty()) {
            return response()->json([
                'books' => [],
                'message' => 'Bu barkoda sahip kitap bulunamadı.',
                'success' => false
            ]);
        }

        // Stokların kitaplarını topla ve unique hale getir
        $bookIds = $stocksByBarcode->pluck('book_id')->unique()->toArray();

        // Bu kitapları tüm detaylarıyla getir
        $books = Book::with(['authors', 'stocks' => function($q) use ($query) {
            $q->where('status', 'available')
              ->where('barcode', 'like', "%{$query}%");
        }])
        ->whereIn('id', $bookIds)
        ->get();

        // Her kitap için kullanılabilir stok sayısını hesapla
        $books->each(function($book) {
            $book->available_stock = $book->stocks->where('status', 'available')->count();
        });

        return response()->json([
            'books' => $books,
            'success' => true,
            'message' => 'Barkod ile kitap bulundu'
        ]);
    }

    public function searchUsers(Request $request)
    {
        $search = $request->get('search');

        $users = User::where(function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->select('id', 'name', 'email')
        ->get()
        ->map(function($user) {
            return [
                'id' => $user->id,
                'text' => $user->name . ' (' . $user->email . ')'
            ];
        });

        return response()->json(['results' => $users]);
    }

    /**
     * Kitap iade tarihini uzatma işlemi
     */
    public function extendDueDate(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);

        // Eğer kitap zaten iade edildiyse veya süre zaten uzatıldıysa hata döndür
        if ($borrowing->return_date !== null) {
            return redirect()->back()
                    ->with('error', 'Bu kitap zaten iade edilmiş, süre uzatılamaz.');
        }

        if ($borrowing->extended_return_date !== null) {
            return redirect()->back()
                    ->with('error', 'Bu kitabın süresi daha önce uzatılmış.');
        }

        // Validasyon
        $validated = $request->validate([
            'extension_days' => 'required|integer|min:1|max:30',
            'extended_date' => 'required|date|after:today',
        ]);

        // Uzatılmış tarihi direkt olarak kullan
        $extendedDate = Carbon::parse($request->extended_date);

        // Uzatılmış tarihi kaydet
        $borrowing->update([
            'extended_return_date' => $extendedDate->format('Y-m-d'),
        ]);

        return redirect()->route('admin.borrowings.index')
                ->with('success', "Kitap teslim tarihi {$request->extension_days} gün uzatıldı. Yeni teslim tarihi: " . $extendedDate->format('d.m.Y'));
    }
}

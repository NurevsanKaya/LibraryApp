<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BorrowingController extends Controller
{
    /**
     * Tüm ödünç kayıtlarını listele
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'book']);
        
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
                $q->whereHas('book', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }
        
        $borrowings = $query->orderBy('created_at', 'desc')
                           ->paginate(10);
        
        return view('admin.borrowings.index', compact('borrowings'));
    }

    /**
     * Yeni ödünç verme formu
     */
    public function create()
    {
        // Tüm kitapları getir
        $books = Book::all();
        
        // Aktif kullanıcıları getir (üye rolündekileri)
        $users = User::where('role_id', 2)
                    ->where('is_active', 1)
                    ->get();
        
        // Şu anda ödünç verilmiş kitapları bul (iade edilmeyenler)
        $borrowedBookIds = Borrowing::whereNull('return_date')
                            ->pluck('book_id')
                            ->toArray();
        
        return view('admin.borrowings.create', compact('books', 'users', 'borrowedBookIds'));
    }

    /**
     * Yeni ödünç kaydı oluştur
     */
    public function store(Request $request)
    {
        // Form verilerini logla (debugging için)
        Log::info('Borrowing store request data:', $request->all());
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
        ]);
        
        // Kitabın ödünç durumunu kontrol et
        $isBookBorrowed = Borrowing::where('book_id', $request->book_id)
                           ->whereNull('return_date')
                           ->exists();
        
        Log::info('Book borrowed check:', ['book_id' => $request->book_id, 'is_borrowed' => $isBookBorrowed]);
        
        if ($isBookBorrowed) {
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu kitap şu anda başka bir kullanıcıda. Ödünç verilemez.');
        }
        
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
        
        Log::info('User borrowing count:', ['user_id' => $request->user_id, 'borrowing_count' => $userBorrowingCount]);
        
        if ($userBorrowingCount >= 5) { // Maksimum 5 kitap ödünç alabilir
            Log::warning('Maximum borrowing limit reached for user', ['user_id' => $request->user_id, 'limit' => 5]);
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu kullanıcı maksimum ödünç alma limitine ulaşmış (5 kitap).');
        }
        
        try {
            // Ödünç kaydı oluştur
            $borrowing = Borrowing::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'borrow_date' => $request->borrow_date,
                'due_date' => $request->due_date,
                'status' => 'active',
            ]);
            
            Log::info('Borrowing created successfully', ['borrowing_id' => $borrowing->id]);
            
            return redirect()->route('admin.borrowings.index')
                    ->with('success', 'Kitap başarıyla ödünç verildi.');
        } catch (\Exception $e) {
            Log::error('Error creating borrowing record', ['error' => $e->getMessage()]);
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ödünç işlemi kaydedilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Belirli bir ödünç kaydını görüntüle
     */
    public function show($id)
    {
        $borrowing = Borrowing::with(['user', 'book'])->findOrFail($id);
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
        
        // Gecikme kontrolü ve ceza işlemi (opsiyonel)
        $dueDate = Carbon::parse($borrowing->due_date);
        $returnDate = Carbon::parse($request->return_date);
        
        if ($returnDate->gt($dueDate)) {
            // Gecikme durumu - uyarı mesajı gösterilebilir
            return redirect()->route('admin.borrowings.index')
                    ->with('warning', 'Kitap gecikmeli olarak iade edildi. (' . $dueDate->diffInDays($returnDate) . ' gün gecikme)');
        }
        
        return redirect()->route('admin.borrowings.index')
                ->with('success', 'Kitap başarıyla iade alındı.');
    }

    /**
     * Kitap arama API endpoint'i
     */
    public function searchBooks(Request $request)
    {
        $query = $request->input('query');
        
        if (!$query) {
            return response()->json(['books' => [], 'borrowedBookIds' => []]);
        }
        
        // ISBN veya kitap adına göre ara
        $books = Book::with('authors')
                    ->where('isbn', 'like', "%{$query}%")
                    ->orWhere('name', 'like', "%{$query}%")
                    ->orWhereHas('authors', function($q) use ($query) {
                        $q->where('first_name', 'like', "%{$query}%")
                          ->orWhere('last_name', 'like', "%{$query}%");
                    })
                    ->limit(10)
                    ->get();
        
        // Şu anda ödünç verilmiş kitapları bul
        $borrowedBookIds = Borrowing::whereNull('return_date')
                            ->pluck('book_id')
                            ->toArray();
        
        return response()->json([
            'books' => $books,
            'borrowedBookIds' => $borrowedBookIds
        ]);
    }
}

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
        
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_ids' => 'required|array',
            'book_ids.*' => 'nullable|exists:books,id',
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
        $newBooksCount = count(array_filter($request->book_ids));
        
        // Toplam kitap sayısı limiti kontrolü (örneğin en fazla 5)
        if ($userBorrowingCount + $newBooksCount > 5) { // Maksimum 5 kitap ödünç alabilir
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu kullanıcı maksimum ödünç alma limitine ulaşacak. En fazla ' . (5 - $userBorrowingCount) . ' kitap daha ödünç verebilirsiniz.');
        }
        
        // Kitapların ödünç durumunu kontrol et
        $borrowedBookIds = Borrowing::whereNull('return_date')
                          ->whereIn('book_id', $request->book_ids)
                          ->pluck('book_id')
                          ->toArray();
        
        
        
        
        
      
            // Her kitap için ödünç kaydı oluştur
            foreach ($request->book_ids as $index => $bookId) {
                if (empty($bookId)) continue; // Seçilmemiş kitap varsa atla
                
             
                    // Ödünç kaydı oluştur - Borrowing modeline uygun alan adlarını kullan
                    Borrowing::create([
                        'user_id' => $request->user_id,
                        'book_id' => $bookId,
                        'borrow_date' => $request->borrow_dates[$index],
                        'due_date' => $request->due_dates[$index],
                        'status' => 'available',
                    ]);
                    
                    
               
            }
            
            
            return redirect()
            ->route('admin.borrowings.index')
            ->with('success', 'Kitap(lar) başarıyla ödünç verildi.');    
       
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
}

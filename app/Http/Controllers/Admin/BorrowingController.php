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
            'book_ids' => 'required|array',
            'book_ids.*' => 'required|exists:books,id',
            'borrow_dates' => 'required|array',
            'borrow_dates.*' => 'required|date',
            'due_dates' => 'required|array',
            'due_dates.*' => 'required|date',
            'borrow_durations' => 'required|array',
            'borrow_durations.*' => 'required|integer|min:1|max:365',
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
        
        Log::info('User borrowing count:', ['user_id' => $request->user_id, 'borrowing_count' => $userBorrowingCount]);
        
        // Toplam ödünç alınacak kitap sayısı
        $newBooksCount = count(array_filter($request->book_ids));
        
        // Mevcut + yeni kitaplar toplam limiti aşıyor mu kontrol et
        if ($userBorrowingCount + $newBooksCount > 5) { // Maksimum 5 kitap ödünç alabilir
            Log::warning('Maximum borrowing limit reached for user', [
                'user_id' => $request->user_id, 
                'current_count' => $userBorrowingCount,
                'new_count' => $newBooksCount,
                'limit' => 5
            ]);
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bu kullanıcı maksimum ödünç alma limitine ulaşacak. En fazla ' . (5 - $userBorrowingCount) . ' kitap daha ödünç verebilirsiniz.');
        }
        
        // Kitapların ödünç durumunu kontrol et
        $borrowedBookIds = Borrowing::whereNull('return_date')
                          ->whereIn('book_id', $request->book_ids)
                          ->pluck('book_id')
                          ->toArray();
        
        if (!empty($borrowedBookIds)) {
            $borrowedBooks = Book::whereIn('id', $borrowedBookIds)->pluck('name')->implode(', ');
            return redirect()->back()
                    ->withInput()
                    ->with('error', 'Seçtiğiniz kitaplardan bazıları şu anda başka kullanıcılarda. Ödünç verilemeyen kitaplar: ' . $borrowedBooks);
        }
        
        $successCount = 0;
        $errors = [];
        
        try {
            // Her kitap için ödünç kaydı oluştur
            foreach ($request->book_ids as $index => $bookId) {
                if (empty($bookId)) continue; // Boş değerler atla
                
                try {
                    // Ödünç kaydı oluştur - Borrowing modeline uygun alan adlarını kullan
                    $borrowing = Borrowing::create([
                        'user_id' => $request->user_id,
                        'book_id' => $bookId,
                        'borrow_date' => $request->borrow_dates[$index],
                        'due_date' => $request->due_dates[$index],
                        'status' => 'active',
                    ]);
                    
                    Log::info('Borrowing created successfully', ['borrowing_id' => $borrowing->id, 'book_id' => $bookId]);
                    $successCount++;
                } catch (\Exception $e) {
                    $book = Book::find($bookId);
                    $bookName = $book ? $book->name : 'Bilinmeyen kitap';
                    $errors[] = "$bookName kitabı için ödünç kaydı oluşturulamadı: " . $e->getMessage();
                    Log::error('Error creating borrowing record for book', ['book_id' => $bookId, 'error' => $e->getMessage()]);
                }
            }
            
            if ($successCount > 0 && empty($errors)) {
                return redirect()->route('admin.borrowings.index')
                        ->with('success', $successCount . ' kitap başarıyla ödünç verildi.');
            } elseif ($successCount > 0 && !empty($errors)) {
                return redirect()->route('admin.borrowings.index')
                        ->with('warning', $successCount . ' kitap başarıyla ödünç verildi, ancak bazı kitaplar için hata oluştu: ' . implode(', ', $errors));
            } else {
                return redirect()->back()
                        ->withInput()
                        ->with('error', 'Hiçbir kitap ödünç verilemedi. Hatalar: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            Log::error('Error in borrowing store process', ['error' => $e->getMessage()]);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Book;
use App\Models\Shelf;
use App\Models\Bookshelf;
use App\Models\AcquisitionSource;
use Illuminate\Support\Facades\Log;
use Laravel\Pail\ValueObjects\Origin\Console;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Stock::with(['book', 'shelf', 'acquisitionSource', 'borrowings' => function($q) {
            $q->whereNull('return_date');
        }]);

        // Debug için log ekle
        Log::info('Stok Filtresi:', [
            'status' => $request->status,
            'search' => $request->search,
            'tüm_parametreler' => $request->all(),
            'url' => $request->fullUrl()
        ]);

        // Filtrele
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'borrowed') {
                // Ödünç verilen kitaplar için özel sorgu
                $query->where(function($q) {
                    $q->where('status', 'borrowed')
                      ->orWhereHas('borrowings', function($q) {
                          $q->whereNull('return_date')
                            ->where('due_date', '<', now());
                      });
                });

                // Gecikmiş kitapların status'ünü güncelle
                $stocksToUpdate = Stock::whereHas('borrowings', function($q) {
                    $q->whereNull('return_date')
                      ->where('due_date', '<', now());
                })->where('status', '!=', 'borrowed')->get();

                foreach ($stocksToUpdate as $stock) {
                    $stock->update(['status' => 'borrowed']);
                }
            } else {
            $query->where('status', $request->status);
            }
            
            // Debug için log ekle
            Log::info('Filtreleme uygulandı:', [
                'status' => $request->status,
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings(),
                'raw_query' => vsprintf(str_replace(['?'], ['\'%s\''], $query->toSql()), $query->getBindings())
            ]);
        }

        // Barkod ile ara
        if ($request->has('search') && $request->search !== '') {
            $query->where('barcode', 'like', '%' . $request->search . '%');
        }

        $stocks = $query->orderBy('id', 'desc')->paginate(10);
        
        // Debug için log ekle
        Log::info('Sonuç:', [
            'stok_sayısı' => $stocks->count(),
            'toplam_sayfa' => $stocks->lastPage(),
            'mevcut_sayfa' => $stocks->currentPage(),
            'her_sayfadaki_kayıt' => $stocks->perPage(),
            'ilk_kayıt' => $stocks->firstItem(),
            'son_kayıt' => $stocks->lastItem()
        ]);

        $shelves = Shelf::all();
        $acquisitionSources = AcquisitionSource::all();
        return view('admin.stocks.index', 
            compact('stocks', 'shelves', 'acquisitionSources')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:50|unique:stocks',
            'book_id' => 'required|exists:books,id',
            'shelf_id' => 'required|exists:shelves,id',
            'acquisition_source_id' => 'nullable|exists:acquisition_source,id',
            'acquisition_price' => 'nullable|numeric|min:0',
            'acquisition_date' => 'nullable|date',
        ]);

        // Status değerini her zaman available olarak ayarla
        $validated['status'] = 'available';

        Stock::create($validated);

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stok başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        $stock->load(['book', 'shelf.bookshelf.location', 'acquisitionSource']);
        return response()->json(['stock' => $stock]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:50|unique:stocks,barcode,' . $stock->id,
            'book_id' => 'required|exists:books,id',
            'shelf_id' => 'required|exists:shelves,id',
            'acquisition_source_id' => 'nullable|exists:acquisition_source,id',
            'acquisition_price' => 'nullable|numeric|min:0',
            'acquisition_date' => 'nullable|date',
        ]);

        $stock->update($validated);

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stok başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stok başarıyla silindi.');
    }

    /**
     * Search for a book by ISBN or name
     */
    public function searchBook(Request $request)
    {
        Log::info('Arama isteği alındı:', [
            'isbn' => $request->isbn,
            'tüm_parametreler' => $request->all()
        ]);
        
        $query = Book::with(['authors' => function($query) {
            $query->select('authors.id', 'first_name', 'last_name');
        }]);

        if ($request->has('isbn')) {
            // Tam eşleşme için 'like' operatörünü kaldırıyoruz
            $query->where('isbn', $request->isbn);
        }

        // SQL sorgusunu logla
        Log::info('SQL Sorgusu:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $book = $query->first();

        // Sonucu logla
        Log::info('Arama sonucu:', [
            'kitap_bulundu' => !is_null($book),
            'kitap_detayları' => $book
        ]);
        
        return response()->json([
            'book' => $book,
            'success' => !is_null($book),
            'message' => $book ? 'Kitap bulundu.' : 'Kitap bulunamadı.'
        ]);
    }

    /**
     * Kitap için uygun rafları getir
     */
    public function getAvailableShelves(Request $request)
    {
        $book = Book::findOrFail($request->book_id);
        
        // Önce aynı ISBN'ye sahip kitapların rafını kontrol et
        $existingShelf = Stock::whereHas('book', function($query) use ($book) {
            $query->where('isbn', $book->isbn);
        })->first();

        if ($existingShelf) {
            // Aynı ISBN'li kitabın rafında yer var mı kontrol et
            $stockCount = Stock::where('shelf_id', $existingShelf->shelf_id)->count();
            if ($stockCount < 10) {
                $shelf = Shelf::find($existingShelf->shelf_id);
                $shelf->stock_count = $stockCount;
                return response()->json([
                    'shelves' => [$shelf],
                    'message' => 'Aynı kitap bu rafta bulunuyor'
                ]);
            }
        }

        // Kategori ve türe göre kitaplıkları bul
        $bookShelves = Bookshelf::with('shelves.stocks')
            ->where('category_id', $book->category_id)
            ->where('genre_id', $book->genres_id)
            ->get();

        // Bu kategori ve türde daha önce kitap eklenmiş mi kontrol et
        $hasExistingBooks = Stock::whereHas('book', function($query) use ($book) {
            $query->where('category_id', $book->category_id)
                  ->where('genres_id', $book->genres_id);
        })->exists();

        if ($hasExistingBooks && !$bookShelves->isEmpty()) {
            // Tüm rafları al ve doluluk kontrolü yap
            $shelves = $bookShelves->flatMap->shelves
                ->filter(function ($shelf) {
                    return $shelf->stocks->count() < 10;
                })
                ->map(function ($shelf) {
                    $stockCount = $shelf->stocks->count();
                    $shelf->stock_count = $stockCount;
                    return $shelf;
                });

            if (!$shelves->isEmpty()) {
                return response()->json([
                    'shelves' => $shelves->values(),
                    'message' => 'Aynı kategori ve türdeki raflar'
                ]);
            }
        }

        // Eğer bu kategori ve türde hiç kitap yoksa veya uygun raf bulunamazsa
        // sadece tamamen boş rafları getir
        $emptyShelves = Shelf::with('stocks')
            ->get()
            ->filter(function ($shelf) {
                return $shelf->stocks->count() === 0;
            })
            ->map(function ($shelf) {
                $shelf->stock_count = 0;
                return $shelf;
            });

        if (!$emptyShelves->isEmpty()) {
            return response()->json([
                'shelves' => $emptyShelves->values(),
                'message' => 'Boş raflar'
            ]);
        }

        // Hiç boş raf yoksa, en az dolu olan rafları getir
        $leastFilledShelves = Shelf::with('stocks')
            ->get()
            ->filter(function ($shelf) {
                return $shelf->stocks->count() < 10;
            })
            ->sortBy(function ($shelf) {
                return $shelf->stocks->count();
            })
            ->take(5)
            ->map(function ($shelf) {
                $stockCount = $shelf->stocks->count();
                $shelf->stock_count = $stockCount;
                return $shelf;
            });

        return response()->json([
            'shelves' => $leastFilledShelves->values(),
            'message' => 'En az dolu raflar'
        ]);
    }
}

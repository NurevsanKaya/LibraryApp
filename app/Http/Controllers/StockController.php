<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Stock;
use App\Models\Shelf;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Stock::with(['book', 'shelf']);

        // Filtrele
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Barkod ile ara
        if ($request->has('search') && $request->search !== '') {
            $query->where('barcode', 'like', '%' . $request->search . '%');
        }

        $stocks = $query->orderBy('id', 'desc')->paginate(10);
        $shelves = Shelf::all();

        return view('admin.stocks.index', compact('stocks', 'shelves'));
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
            'acquisition_source' => 'nullable|string|max:255',
            'acquisition_price' => 'nullable|numeric|min:0',
            'acquisition_date' => 'nullable|date',
            'status' => 'required|in:active,borrowed',
        ]);

        Stock::create($validated);

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stok başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        $stock->load(['book', 'shelf']);
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
            'acquisition_source' => 'nullable|string|max:255',
            'acquisition_price' => 'nullable|numeric|min:0',
            'acquisition_date' => 'nullable|date',
            'status' => 'required|in:active,borrowed',
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
     * Search for a book by ISBN
     */
    public function searchBook(Request $request)
    {
        \Log::info('Search request received for ISBN: ' . $request->isbn);
        
        $book = Book::with(['authors' => function($query) {
            $query->select('authors.id', 'first_name', 'last_name');
        }])
        ->where('isbn', $request->isbn)
        ->first();
            
        \Log::info('Search result:', ['book' => $book]);
        
        return response()->json(['book' => $book]);
    }
}

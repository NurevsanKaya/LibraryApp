<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Categories;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        try {
            // Debug için
            \Log::info('Book ID: ' . $book->id);
            \Log::info('Book Name: ' . $book->name);
            
            // İlişkileri yükle
            $book->load(['authors', 'publisher', 'category', 'stocks']);
            
            // İlişkileri kontrol et
            \Log::info('Authors: ' . $book->authors->count());
            \Log::info('Publisher: ' . ($book->publisher ? 'exists' : 'null'));
            \Log::info('Category: ' . ($book->category ? 'exists' : 'null'));
            
            return view('books.show', compact('book'));
        } catch (\Exception $e) {
            \Log::error('Book show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Kitap bilgileri yüklenirken bir hata oluştu.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Ana arama sorgusu
        $books = Book::with(['authors', 'publisher'])
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhereHas('authors', function ($q) use ($query) {
                      $q->where('name', 'LIKE', '%' . $query . '%');
                  });
            })
            ->paginate(10);

        // Benzer kitapları bul (eğer sonuç yoksa)
        $suggestions = null;
        if ($books->isEmpty()) {
            $suggestions = Book::with(['authors', 'publisher'])
                ->where('name', 'LIKE', '%' . substr($query, 0, 3) . '%')
                ->orWhere('name', 'LIKE', '%' . substr($query, -3) . '%')
                ->take(5)
                ->get();
        }

        return view('search-results', compact('books', 'query', 'suggestions'));
    }
}


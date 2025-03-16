<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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
        //
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
        $query = $request->input('query'); // Kullanıcının arama sorgusu

        // Kitap adına veya yazar adına göre arama yap
        $books = Book::where('name', 'LIKE', '%' . $query . '%')
            ->orWhereHas('authors', function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%'); // Yazar adına göre arama
            })
            ->get();

        // Sonuçları bir view dosyasına gönder
        return view('search-results', compact('books', 'query'));
    }

}


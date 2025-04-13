<?php

namespace App\Http\Controllers;

use App\Models\AcquisitionSource;
use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use App\Models\Genres;
use App\Models\Borrowing;
use App\Models\Categories;
use App\Models\Publisher;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function dashboard()
    {
        // Get statistics for dashboard
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $activeBorrowings = Borrowing::whereNull('return_date')->count();
        $totalCategories = Categories::count();

        // Get recent borrowings
        $recentBorrowings = Borrowing::with(['user', 'stock.book'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalUsers',
            'activeBorrowings',
            'totalCategories',
            'recentBorrowings'
        ));
    }


    public function books()
    {
        $books = Book::with(['category', 'authors', 'publisher'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $categories = Categories::all();
        $publishers = Publisher::all();
        $genres = Genres::all();
        $authors = Author::all();

        return view('admin.books.index', compact(
            'books',
            'categories',
            'publishers',
            'genres',
            'authors'
        ));
    }

    /**
     * Veri ekleme sayfasını gösterir (Yayınevi, Kategori, Tür, Yazar)
     */
    public function dataAdding()
    {
        $publishers = Publisher::orderBy('name')->get();
        $categories = Categories::orderBy('name')->get();
        $genres = Genres::orderBy('name')->get();
        $authors = Author::orderBy('first_name')->get();
        $acquisition_source=AcquisitionSource::orderBy('name')->get();

        return view('admin.data-adding', compact(
            'publishers',
            'categories',
            'genres',
            'authors',
            'acquisition_source'
        ));
    }

    public function storeBook(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books',
            'publication_year' => 'required|integer|min:1000|max:' . date('Y'),//Maksimum olarak günümüz yılını (max:date('Y'))yapabiliriz
            'publisher_id' => 'required|exists:publishers,id',
            'genres_id' => 'required|exists:genres,id',
            'category_id' => 'required|exists:categories,id',
            'authors' => 'required|array',// authors bir dizi olmalı
            'authors.*' => 'exists:authors,id',// Dizi içindeki her eleman authors tablosunda olmalı
        ]);

        $book = Book::create([
            'name' => $validated['name'],
            'isbn' => $validated['isbn'],
            'publication_year' => $validated['publication_year'],
            'publisher_id' => $validated['publisher_id'],
            'genres_id' => $validated['genres_id'],
            'category_id' => $validated['category_id'],
        ]);

        $book->authors()->attach($validated['authors']); //BookAuthors tablosuna yazarlar ekledik

        return redirect()->route('admin.books.index')
            ->with('success', 'Kitap başarıyla eklendi.');
    }


    public function updateBook(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $id,
            'publication_year' => 'required|integer|min:1000|max:' . date('Y'),
            'publisher_id' => 'required|exists:publishers,id',
            'genres_id' => 'required|exists:genres,id',
            'category_id' => 'required|exists:categories,id',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book->update([
            'name' => $validated['name'],
            'isbn' => $validated['isbn'],
            'publication_year' => $validated['publication_year'],
            'publisher_id' => $validated['publisher_id'],
            'genres_id' => $validated['genres_id'],
            'category_id' => $validated['category_id'],
        ]);

        $book->authors()->sync($validated['authors']);

        return redirect()->route('admin.books.index')
            ->with('success', 'Kitap başarıyla güncellendi.');
    }

    /**
     * Get book data for editing
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBook($id)
    {
        $book = Book::with(['authors'])->findOrFail($id);

        return response()->json([
            'book' => $book,
            'authorIds' => $book->authors->pluck('id')->toArray()
        ]);
    }

    public function destroy($id)
    {
        // Favoriyi veri tabanından sil
        $books = Book::find($id);

        if (!$books) {
            return redirect()->back()->with('error', 'Kitap bulunamadı.');
        }

        $books->delete();

        return redirect()->back()->with('success', 'Kitap başarıyla kaldırıldı.');
    }
}

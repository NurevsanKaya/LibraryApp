<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Categories;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get statistics for dashboard
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $activeBorrowings = Borrowing::whereNull('return_date')->count();
        $totalCategories = Categories::count();
        
        // Get recent borrowings
        $recentBorrowings = Borrowing::with(['user', 'book'])
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

    /**
     * Display the books management page.
     *
     * @return \Illuminate\View\View
     */
    public function books()
    {
        $books = Book::with(['category', 'authors', 'publisher'])
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        $categories = Categories::all();
        
        return view('admin.books.index', compact('books', 'categories'));
    }
}

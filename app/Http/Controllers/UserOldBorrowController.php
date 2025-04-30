<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class UserOldBorrowController
{
    public function index()
    {



$borrowings = Borrowing::with(['stock.book.authors', 'stock.book.category'])
    ->where('user_id', auth()->id())
    ->whereNotNull('return_date') // sadece iade edilenleri getir
    ->orderByDesc('return_date')
    ->get();



        return view('user.oldborrowings', compact('borrowings'));
    }

}

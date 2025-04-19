<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class UserBorrowController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // çünkü borrowing tablosu user_id tutuyor

        $borrowings = Borrowing::with('stock.book')
            ->where('user_id', $userId)

            ->get();

        return view('user.borrowings', compact('borrowings'));
    }
}

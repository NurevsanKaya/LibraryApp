<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\PenaltyPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserDashboardController
{
    public function dashboard()
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');

        // Tüm iade edilmemiş kitaplar
        $borrowings = Borrowing::with('stock.book')
            ->where('user_id', $userId)
            ->whereNull('return_date')
            ->get();

        // Gecikmiş kitaplar
        $overdueBooks = Borrowing::with('stock.book')
            ->where('user_id', $userId)
            ->whereNull('return_date')
            ->where('due_date', '<', $today)
            ->get();

        $penalties = PenaltyPayment::where('user_id', $userId)->get();

        return view('dashboard', compact('borrowings', 'penalties', 'overdueBooks'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\PenaltyPayment;

class UserDashboardController
{
    public function dashboard()
    {
        $userId = auth()->id();

        $borrowings = Borrowing::with('stock.book')
            ->where('user_id', $userId)
            ->whereNull('return_date')
            ->get();

        $penalties = PenaltyPayment::where('user_id', $userId)->get();

        return view('dashboard', compact('borrowings', 'penalties'));
    }
}

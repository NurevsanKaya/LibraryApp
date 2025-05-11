<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverdueController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $dailyPenalty = 5; // Günlük ceza miktarı (TL)

        $overdueBooks = Borrowing::with(['stock.book', 'stock.book.category'])
            ->where('user_id', auth()->user()->id)
            ->whereNull('return_date')
            ->where('due_date', '<', $today)
            ->get()
            ->map(function ($borrowing) use ($today, $dailyPenalty) {
                $daysOverdue = (int)$today->diffInDays($borrowing->due_date);
                $currentPenalty = (int)($daysOverdue * $dailyPenalty);
                
                return [
                    'book_name' => $borrowing->stock->book->name,
                    'isbn' => $borrowing->stock->book->isbn,
                    'category' => $borrowing->stock->book->category->name,
                    'borrow_date' => $borrowing->borrow_date,
                    'due_date' => $borrowing->due_date,
                    'days_overdue' => $daysOverdue,
                    'daily_penalty' => $dailyPenalty,
                    'current_penalty' => $currentPenalty,
                ];
            });

        return view('user.overdue.index', compact('overdueBooks'));
    }
} 
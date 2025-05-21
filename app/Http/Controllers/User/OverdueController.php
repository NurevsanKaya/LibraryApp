<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverdueController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = now();

        // Ceza ayarlarını al
        $penaltySettings = \App\Models\PenaltySetting::first();
        $basePenalty = $penaltySettings->base_penalty_fee ?? 0;
        $dailyPenalty = $penaltySettings->daily_penalty_fee ?? 0;

        $overdueBooks = Borrowing::with(['stock.book', 'stock.book.category'])
            ->where('user_id', $userId)
            ->whereNull('return_date')
            ->where(function($query) use ($today) {
                $query->where('due_date', '<', $today)
                    ->orWhere(function($q) use ($today) {
                        $q->whereNotNull('extended_return_date')
                            ->where('extended_return_date', '<', $today);
                    });
            })
            ->get()
            ->map(function($borrowing) use ($today, $basePenalty, $dailyPenalty) {
                $dueDate = $borrowing->extended_return_date 
                    ? Carbon::parse($borrowing->extended_return_date)
                    : Carbon::parse($borrowing->due_date);
                
                // Gecikme gününü hesapla (iade tarihi dahil değil)
                $daysOverdue = max(0, $dueDate->startOfDay()->diffInDays($today->startOfDay()));
                
                // Toplam ceza = Temel ceza + (Günlük ceza * Gecikme günü)
                $currentPenalty = $basePenalty + ($dailyPenalty * $daysOverdue);

                return [
                    'book_name' => $borrowing->stock->book->name ?? 'Silinmiş Kitap',
                    'isbn' => $borrowing->stock->book->isbn ?? 'N/A',
                    'category' => $borrowing->stock->book->category->name ?? 'N/A',
                    'borrow_date' => $borrowing->borrow_date,
                    'due_date' => $borrowing->due_date,
                    'days_overdue' => $daysOverdue,
                    'daily_penalty' => $dailyPenalty,
                    'base_penalty' => $basePenalty,
                    'current_penalty' => $currentPenalty
                ];
            });

        return view('user.overdue.index', compact('overdueBooks'));
    }
} 
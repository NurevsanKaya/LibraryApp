<?php

namespace App\Http\Controllers;


use App\Models\PenaltyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPenaltyController extends Controller
{
    public function index()
    {
        $penalties = PenaltyPayment::where('user_id', auth()->id())->get();

        return view('user.penalties', compact('penalties'));
    }
    public function pay(Request $request, $id)
    {
        // Kullanıcının gerçekten ödeme yöntemi seçtiğinden emin olalım
        $request->validate([
            'payment_method' => 'required|in:nakit,online',
        ]);

        $penalty = PenaltyPayment::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $penalty->update([
            'status' => 'ödendi',
            'payment_date' => now(),
            'payment_method' => $request->payment_method, // 💥 burası eklendi!
        ]);

        return redirect()->back()->with('success', 'Ödeme başarıyla gerçekleştirildi!');
    }
}

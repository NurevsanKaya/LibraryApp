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

    public function uploadReceipt(Request $request, $id): \Illuminate\Http\RedirectResponse
    {


        $request->validate([
            'payment_method' => 'required|in:nakit,havale',
            'receipt' => 'required_if:payment_method,havale|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $penalty = PenaltyPayment::where('user_id', auth()->id())->findOrFail($id);

        $data = [
            'payment_method' => $request->payment_method,
            'payment_date' => now(),
        ];

        if ($request->payment_method === 'havale') {
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
            $data['status'] = 'bekliyor'; // dekont yüklendi
        } else {
            $data['status'] = 'bekliyor'; // nakit olarak memura iletildi
        }

        $penalty->update($data);

        return redirect()->back()->with('success', 'Ödeme işlemi kaydedildi. Onay bekleniyor.');
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\PenaltyPayment;
use Illuminate\Http\Request;

class PenaltyPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penalties = PenaltyPayment::with('user')->latest()->get();
        return view('admin.payments.index', compact('penalties'));
    }

    public function approve($id)
    {
        $penalty = PenaltyPayment::findOrFail($id);
        $penalty->update(['status' => 'onaylandı']);
        return back()->with('success', 'Ceza ödemesi onaylandı.');
    }

    public function reject($id)
    {
        $penalty = PenaltyPayment::findOrFail($id);
        $penalty->update(['status' => 'reddedildi']);
        return back()->with('success', 'Ceza ödemesi reddedildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PenaltyPayment $penaltyPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenaltyPayment $penaltyPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenaltyPayment $penaltyPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenaltyPayment $penaltyPayment)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PenaltySetting;
use Illuminate\Http\Request;

class PenaltySettingController extends Controller
{
    /**
     * Ceza ayarları sayfasını gösterir
     */
    public function index()
    {
        // İlk kaydı al veya yoksa oluştur
        $settings = PenaltySetting::firstOrCreate(
            [],
            [
                'base_penalty_fee' => 50,
                'daily_penalty_fee' => 5
            ]
        );
        
        return view('admin.payments.settings', compact('settings'));
    }
    
    /**
     * Ceza ayarlarını günceller
     */
    public function update(Request $request)
    {
        $request->validate([
            'base_penalty_fee' => 'required|numeric|min:0',
            'daily_penalty_fee' => 'required|numeric|min:0',
        ]);
        
        $settings = PenaltySetting::first();
        
        if (!$settings) {
            $settings = new PenaltySetting();
        }
        
        $settings->base_penalty_fee = $request->base_penalty_fee;
        $settings->daily_penalty_fee = $request->daily_penalty_fee;
        $settings->save();
        
        return redirect()->route('admin.penalty.settings')
            ->with('success', 'Ceza ayarları başarıyla güncellendi.');
    }
} 
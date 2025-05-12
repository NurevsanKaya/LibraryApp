<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\PenaltySetting;
use App\Models\PenaltyPayment;
use Carbon\Carbon;

class PenaltyService
{
    /**
     * Gecikmiş bir ödünç için ceza tutarını hesaplar
     * 
     * @param Borrowing $borrowing
     * @return array
     */
    public function calculatePenalty(Borrowing $borrowing)
    {
        // Eğer iade edilmediyse veya gecikmemişse ceza yok
        if (!$borrowing->return_date || Carbon::parse($borrowing->return_date)->lte(Carbon::parse($borrowing->due_date))) {
            return [
                'has_penalty' => false,
                'days_late' => 0,
                'amount' => 0
            ];
        }
        
        // Gecikme gününü hesapla
        $dueDate = Carbon::parse($borrowing->due_date);
        $returnDate = Carbon::parse($borrowing->return_date);
        $daysLate = $dueDate->diffInDays($returnDate);
        
        // Ceza ayarlarını veritabanından al
        $settings = PenaltySetting::first(); // Tek kayıt olacağını varsayıyoruz
        
        if (!$settings) {
            // Varsayılan değerler
            $baseFee = 50;
            $dailyFee = 5;
        } else {
            $baseFee = $settings->base_penalty_fee;
            $dailyFee = $settings->daily_penalty_fee;
        }
        
        // Toplam cezayı hesapla
        $totalAmount = $baseFee + ($daysLate * $dailyFee);
        
        return [
            'has_penalty' => true,
            'days_late' => $daysLate,
            'base_amount' => $baseFee,
            'daily_rate' => $dailyFee,
            'amount' => $totalAmount
        ];
    }
    
    /**
     * Ceza kaydı oluşturur veya günceller
     * 
     * @param Borrowing $borrowing
     * @return PenaltyPayment|null
     */
    public function createOrUpdatePenalty(Borrowing $borrowing)
    {
        $penaltyData = $this->calculatePenalty($borrowing);
        
        if (!$penaltyData['has_penalty']) {
            return null;
        }
        
        // Mevcut ceza kaydı var mı kontrol et
        $existingPenalty = PenaltyPayment::where('borrowing_id', $borrowing->id)->first();
        
        if ($existingPenalty) {
            $existingPenalty->update([
                'amount' => $penaltyData['amount'],
                'payment_date' => now(),
                'days_late' => $penaltyData['days_late'],
                'base_amount' => $penaltyData['base_amount'],
                'daily_rate' => $penaltyData['daily_rate']
            ]);
            
            return $existingPenalty;
        }
        
        // Yeni ceza kaydı oluştur
        return PenaltyPayment::create([
            'user_id' => $borrowing->user_id,
            'borrowing_id' => $borrowing->id,
            'amount' => $penaltyData['amount'],
            'payment_date' => now(),
            'payment_method' => 'nakit', // Varsayılan olarak nakit
            'status' => 'ödeme bekleniyor',
            'days_late' => $penaltyData['days_late'],
            'base_amount' => $penaltyData['base_amount'],
            'daily_rate' => $penaltyData['daily_rate']
        ]);
    }
} 
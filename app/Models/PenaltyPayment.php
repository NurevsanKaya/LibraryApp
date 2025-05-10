<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenaltyPayment extends Model
{
    use HasFactory;
    protected $table = 'penalty_payments';
    protected $casts = [
        'payment_date' => 'date',
    ];
    protected $fillable = ['user_id', 'borrowing_id', 'amount', 'payment_date', 'payment_method', 'status', 'receipt_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}

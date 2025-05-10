<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenaltySetting extends Model
{
    use HasFactory;
    
    protected $table = 'penalty_settings';
    
    protected $fillable = [
        'base_penalty_fee',
        'daily_penalty_fee'
    ];
} 
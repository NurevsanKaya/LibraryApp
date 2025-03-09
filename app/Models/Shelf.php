<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;
    protected $table = 'shelves';

    protected $fillable = ['shelf_number', 'bookshelf_id'];


    public function bookshelf()
    {
        return $this->belongsTo(Bookshelf::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }    
}

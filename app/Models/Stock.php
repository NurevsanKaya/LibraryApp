<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stocks';

    protected $fillable = ['barcode','book_id', 'shelf_id', 'acquisition_source', 'acquisition_price', 'acquisition_date', 'status'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookshelf extends Model
{
    use HasFactory;
    protected $table = 'bookshelves';

    protected $fillable = ['bookshelf_number', 'category_id', 'genre_id', 'location_id'];
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function shelves()
    {
        return $this->hasMany(Shelf::class);
    }
}

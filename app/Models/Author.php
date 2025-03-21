<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{

    use HasFactory;
    protected $table = 'authors';

    protected $fillable = ['first_name', 'last_name'];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_authors');
    }
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

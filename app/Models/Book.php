<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';

    protected $fillable = ['name', 'publication_year', 'publisher_id', 'isbn', 'genres_id', 'category_id'];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genres::class, 'genres_id');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors');
    }
}

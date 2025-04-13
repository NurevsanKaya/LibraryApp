<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Categories;
use App\Models\Genres;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Gerekli ilişkilerin var olduğundan emin olalım
            $author1 = Author::firstOrCreate([
                'first_name' => 'Orhan',
                'last_name' => 'Pamuk'
            ]);
            
            $author2 = Author::firstOrCreate([
                'first_name' => 'Yaşar',
                'last_name' => 'Kemal'
            ]);
            
            $author3 = Author::firstOrCreate([
                'first_name' => 'Sabahattin',
                'last_name' => 'Ali'
            ]);

            $publisher1 = Publisher::firstOrCreate(['name' => 'Yapı Kredi Yayınları']);
            $publisher2 = Publisher::firstOrCreate(['name' => 'İş Bankası Kültür Yayınları']);
            $publisher3 = Publisher::firstOrCreate(['name' => 'Can Yayınları']);

            $category = Categories::firstOrCreate(['name' => 'Roman']);
            $genre1 = Genres::firstOrCreate(['name' => 'Edebiyat']);
            $genre2 = Genres::firstOrCreate(['name' => 'Klasik']);
            
            // Kitap 1
            $book1 = Book::firstOrCreate(
                ['isbn' => '9789750826603'],
                [
                    'name' => 'Masumiyet Müzesi',
                    'publisher_id' => $publisher1->id,
                    'publication_year' => 2008,
                    'category_id' => $category->id,
                    'genres_id' => $genre1->id,
                ]
            );
            
            // Kitap 1'e yazar ekleme
            if (!$book1->authors()->where('author_id', $author1->id)->exists()) {
                $book1->authors()->attach($author1->id);
            }
            
            // Kitap 2
            $book2 = Book::firstOrCreate(
                ['isbn' => '9789754370249'],
                [
                    'name' => 'İnce Memed',
                    'publisher_id' => $publisher3->id,
                    'publication_year' => 1955,
                    'category_id' => $category->id,
                    'genres_id' => $genre1->id,
                ]
            );
            
            // Kitap 2'ye yazar ekleme
            if (!$book2->authors()->where('author_id', $author2->id)->exists()) {
                $book2->authors()->attach($author2->id);
            }
            
            // Kitap 3
            $book3 = Book::firstOrCreate(
                ['isbn' => '9789754705089'],
                [
                    'name' => 'Kürk Mantolu Madonna',
                    'publisher_id' => $publisher2->id,
                    'publication_year' => 1943,
                    'category_id' => $category->id,
                    'genres_id' => $genre2->id,
                ]
            );
            
            // Kitap 3'e yazar ekleme
            if (!$book3->authors()->where('author_id', $author3->id)->exists()) {
                $book3->authors()->attach($author3->id);
            }
            
            $this->command->info('3 kitap başarıyla eklendi.');
        });
    }
} 
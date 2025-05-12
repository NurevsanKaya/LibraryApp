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
            // 30 Gerçekçi Kitap Verisi
            
            // Kitap bilgileri - ID'ler yerine isimler
            $books = [
                [
                    'name' => 'Masumiyet Müzesi',
                    'isbn' => '9789750826603',
                    'publication_year' => 2008,
                    'publisher_name' => 'Yapı Kredi Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Orhan',
                    'author_last_name' => 'Pamuk'
                ],
                [
                    'name' => 'İnce Memed',
                    'isbn' => '9789754370249',
                    'publication_year' => 1955,
                    'publisher_name' => 'Yapı Kredi Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Yaşar',
                    'author_last_name' => 'Kemal'
                ],
                [
                    'name' => 'Kürk Mantolu Madonna',
                    'isbn' => '9789754705089',
                    'publication_year' => 1943,
                    'publisher_name' => 'İş Bankası Kültür Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Sabahattin',
                    'author_last_name' => 'Ali'
                ],
                [
                    'name' => 'Aşk',
                    'isbn' => '9789750738609',
                    'publication_year' => 2009,
                    'publisher_name' => 'Doğan Kitap',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Elif',
                    'author_last_name' => 'Şafak'
                ],
                [
                    'name' => 'Beyoğlu Rapsodisi',
                    'isbn' => '9789750740121',
                    'publication_year' => 2003,
                    'publisher_name' => 'Everest Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Polisiye/Gerilim',
                    'author_first_name' => 'Ahmet',
                    'author_last_name' => 'Ümit'
                ],
                [
                    'name' => 'Huzursuzluk',
                    'isbn' => '9789750845840',
                    'publication_year' => 2017,
                    'publisher_name' => 'Doğan Kitap',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Zülfü',
                    'author_last_name' => 'Livaneli'
                ],
                [
                    'name' => 'Kanadı Kırık Kuşlar',
                    'isbn' => '9789751410849',
                    'publication_year' => 2018,
                    'publisher_name' => 'Everest Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Tarih',
                    'author_first_name' => 'Ayşe',
                    'author_last_name' => 'Kulin'
                ],
                [
                    'name' => 'Tutunamayanlar',
                    'isbn' => '9789754702699',
                    'publication_year' => 1972,
                    'publisher_name' => 'İletişim Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Oğuz',
                    'author_last_name' => 'Atay'
                ],
                [
                    'name' => '9. Hariciye Koğuşu',
                    'isbn' => '9789759957650',
                    'publication_year' => 1930,
                    'publisher_name' => 'Ötüken Neşriyat',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Peyami',
                    'author_last_name' => 'Safa'
                ],
                [
                    'name' => 'Bir Ömür Nasıl Yaşanır?',
                    'isbn' => '9786051850825',
                    'publication_year' => 2019,
                    'publisher_name' => 'Kronik Kitap',
                    'category_name' => 'Kurgu Dışı / Bilgi',
                    'genre_name' => 'Kişisel Gelişim',
                    'author_first_name' => 'İlber',
                    'author_last_name' => 'Ortaylı'
                ],
                [
                    'name' => 'Sapiens: İnsan Türünün Kısa Bir Tarihi',
                    'isbn' => '9786059926920',
                    'publication_year' => 2015,
                    'publisher_name' => 'Kolektif Kitap',
                    'category_name' => 'Kurgu Dışı / Bilgi',
                    'genre_name' => 'Tarih',
                    'author_first_name' => 'Yuval Noah',
                    'author_last_name' => 'Harari'
                ],
                [
                    'name' => '1984',
                    'isbn' => '9789750738616',
                    'publication_year' => 1949,
                    'publisher_name' => 'Can Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Bilim Kurgu',
                    'author_first_name' => 'George',
                    'author_last_name' => 'Orwell'
                ],
                [
                    'name' => 'Harry Potter ve Felsefe Taşı',
                    'isbn' => '9789750802720',
                    'publication_year' => 1997,
                    'publisher_name' => 'Yapı Kredi Yayınları',
                    'category_name' => 'Çocuk ve Gençlik',
                    'genre_name' => 'Fantastik',
                    'author_first_name' => 'J.K.',
                    'author_last_name' => 'Rowling'
                ],
                [
                    'name' => 'Suç ve Ceza',
                    'isbn' => '9789750736193',
                    'publication_year' => 1866,
                    'publisher_name' => 'İş Bankası Kültür Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Fyodor',
                    'author_last_name' => 'Dostoyevski'
                ],
                [
                    'name' => 'Yabancı',
                    'isbn' => '9789750733697',
                    'publication_year' => 1942,
                    'publisher_name' => 'Can Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Albert',
                    'author_last_name' => 'Camus'
                ],
                [
                    'name' => 'Kar',
                    'isbn' => '9789750802928',
                    'publication_year' => 2002,
                    'publisher_name' => 'Yapı Kredi Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Orhan',
                    'author_last_name' => 'Pamuk'
                ],
                [
                    'name' => 'Yer Demir Gök Bakır',
                    'isbn' => '9789754370256',
                    'publication_year' => 1963,
                    'publisher_name' => 'Yapı Kredi Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Yaşar',
                    'author_last_name' => 'Kemal'
                ],
                [
                    'name' => 'İçimizdeki Şeytan',
                    'isbn' => '9789754705072',
                    'publication_year' => 1940,
                    'publisher_name' => 'İş Bankası Kültür Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Sabahattin',
                    'author_last_name' => 'Ali'
                ],
                [
                    'name' => 'İskender',
                    'isbn' => '9789750738623',
                    'publication_year' => 2011,
                    'publisher_name' => 'Doğan Kitap',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Elif',
                    'author_last_name' => 'Şafak'
                ],
                [
                    'name' => 'Sultanı Öldürmek',
                    'isbn' => '9789750740176',
                    'publication_year' => 2012,
                    'publisher_name' => 'Everest Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Polisiye/Gerilim',
                    'author_first_name' => 'Ahmet',
                    'author_last_name' => 'Ümit'
                ],
                [
                    'name' => 'Serenad',
                    'isbn' => '9789750844652',
                    'publication_year' => 2011,
                    'publisher_name' => 'Doğan Kitap',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Tarih',
                    'author_first_name' => 'Zülfü',
                    'author_last_name' => 'Livaneli'
                ],
                [
                    'name' => 'Nefes Nefese',
                    'isbn' => '9789751410825',
                    'publication_year' => 2002,
                    'publisher_name' => 'Everest Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Tarih',
                    'author_first_name' => 'Ayşe',
                    'author_last_name' => 'Kulin'
                ],
                [
                    'name' => 'Tehlikeli Oyunlar',
                    'isbn' => '9789754702675',
                    'publication_year' => 1973,
                    'publisher_name' => 'İletişim Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Oğuz',
                    'author_last_name' => 'Atay'
                ],
                [
                    'name' => 'Matmazel Noraliya\'nın Koltuğu',
                    'isbn' => '9789759957667',
                    'publication_year' => 1949,
                    'publisher_name' => 'Ötüken Neşriyat',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Peyami',
                    'author_last_name' => 'Safa'
                ],
                [
                    'name' => 'Türklerin Tarihi',
                    'isbn' => '9786054729951',
                    'publication_year' => 2015,
                    'publisher_name' => 'Timaş Yayınları',
                    'category_name' => 'Kurgu Dışı / Bilgi',
                    'genre_name' => 'Tarih',
                    'author_first_name' => 'İlber',
                    'author_last_name' => 'Ortaylı'
                ],
                [
                    'name' => 'Homo Deus: Yarının Kısa Bir Tarihi',
                    'isbn' => '9786059926944',
                    'publication_year' => 2016,
                    'publisher_name' => 'Kolektif Kitap',
                    'category_name' => 'Kurgu Dışı / Bilgi',
                    'genre_name' => 'Bilim',
                    'author_first_name' => 'Yuval Noah',
                    'author_last_name' => 'Harari'
                ],
                [
                    'name' => 'Hayvan Çiftliği',
                    'isbn' => '9789750738593',
                    'publication_year' => 1945,
                    'publisher_name' => 'Can Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'George',
                    'author_last_name' => 'Orwell'
                ],
                [
                    'name' => 'Harry Potter ve Sırlar Odası',
                    'isbn' => '9789750802737',
                    'publication_year' => 1998,
                    'publisher_name' => 'Yapı Kredi Yayınları',
                    'category_name' => 'Çocuk ve Gençlik',
                    'genre_name' => 'Fantastik',
                    'author_first_name' => 'J.K.',
                    'author_last_name' => 'Rowling'
                ],
                [
                    'name' => 'Karamazov Kardeşler',
                    'isbn' => '9789750736186',
                    'publication_year' => 1880,
                    'publisher_name' => 'İş Bankası Kültür Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Fyodor',
                    'author_last_name' => 'Dostoyevski'
                ],
                [
                    'name' => 'Veba',
                    'isbn' => '9789750733628',
                    'publication_year' => 1947,
                    'publisher_name' => 'Can Yayınları',
                    'category_name' => 'Kurgu / Roman',
                    'genre_name' => 'Roman',
                    'author_first_name' => 'Albert',
                    'author_last_name' => 'Camus'
                ]
            ];

            // Kitapları oluştur
            foreach ($books as $bookData) {
                // Gerekli ilişkileri bul
                $publisher = Publisher::where('name', $bookData['publisher_name'])->first();
                $category = Categories::where('name', $bookData['category_name'])->first();
                $genre = Genres::where('name', $bookData['genre_name'])->first();
                $author = Author::where('first_name', $bookData['author_first_name'])
                    ->where('last_name', $bookData['author_last_name'])
                    ->first();
                
                // Eğer bazı isimler bulunamazsa, varsayılan olarak bir tane oluştur
                if (!$publisher) {
                    $publisher = Publisher::firstOrCreate(['name' => $bookData['publisher_name']]);
                }
                
                if (!$category) {
                    $category = Categories::firstOrCreate(['name' => $bookData['category_name']]);
                }
                
                if (!$genre) {
                    $genre = Genres::firstOrCreate(['name' => $bookData['genre_name']]);
                }
                
                if (!$author) {
                    $author = Author::firstOrCreate([
                        'first_name' => $bookData['author_first_name'],
                        'last_name' => $bookData['author_last_name']
                    ]);
                }
                
                // Kitabı oluştur
                $book = Book::firstOrCreate(
                    ['isbn' => $bookData['isbn']],
                    [
                        'name' => $bookData['name'],
                        'publication_year' => $bookData['publication_year'],
                        'publisher_id' => $publisher->id,
                        'category_id' => $category->id,
                        'genres_id' => $genre->id
                    ]
                );

                // Kitap-Yazar ilişkisini oluştur
                if (!$book->authors()->where('author_id', $author->id)->exists()) {
                    $book->authors()->attach($author->id);
                }
            }
            
            $this->command->info('30 kitap başarıyla eklendi.');
        });
    }
} 
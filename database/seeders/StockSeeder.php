<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Stock;
use App\Models\AcquisitionSource;
use App\Models\Shelf;
use App\Models\Bookshelf;
use App\Models\Categories;
use App\Models\Genres;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Gerekli ilişkilerin var olduğundan emin olalım
            $acquisitionSource = AcquisitionSource::firstOrCreate(['name' => 'Satın Alma']);
            
            // Location, Bookshelf ve Shelf oluştur
            $category = Categories::firstOrCreate(['name' => 'Roman']);
            $genre = Genres::firstOrCreate(['name' => 'Edebiyat']);
            
            $location = Location::firstOrCreate([
                'building_number' => 'A',
                'room_number' => '101', 
                'floor_number' => '1'
            ]);
            
            $bookshelf = Bookshelf::firstOrCreate([
                'bookshelf_number' => 'A',
                'category_id' => $category->id,
                'genre_id' => $genre->id,
                'location_id' => $location->id
            ]);
            
            $shelf = Shelf::firstOrCreate([
                'shelf_number' => 'A-1',
                'bookshelf_id' => $bookshelf->id
            ]);
            
            // Tüm kitapları getir
            $books = Book::all();
            
            // Her kitap için 2-3 stok kaydı oluştur
            foreach ($books as $book) {
                // Her kitap için kaç stok oluşturulacak
                $stockCount = rand(2, 3);
                
                for ($i = 1; $i <= $stockCount; $i++) {
                    $barcode = 'BK' . str_pad($book->id, 4, '0', STR_PAD_LEFT) . '-' . $i;
                    
                    Stock::firstOrCreate(
                        ['barcode' => $barcode],
                        [
                            'book_id' => $book->id,
                            'shelf_id' => $shelf->id,
                            'acquisition_source_id' => $acquisitionSource->id,
                            'acquisition_price' => rand(20, 100) . '.00',
                            'acquisition_date' => now()->subDays(rand(1, 365))->format('Y-m-d'),
                            'status' => 'available'
                        ]
                    );
                }
            }
            
            $this->command->info('Kitap stokları başarıyla oluşturuldu.');
        });
    }
} 
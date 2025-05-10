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
use Carbon\Carbon;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Önce stocks tablosunu temizle
            DB::table('stocks')->truncate();

            $books = Book::all();
            $shelves = Shelf::all();
            $sources = AcquisitionSource::all();
            $today = Carbon::now();

            foreach ($books as $book) {
                $stockCount = rand(2, 4);
                $usedShelves = [];
                for ($i = 0; $i < $stockCount; $i++) {
                    // Barkod: YILAYGÜN + 4 haneli random
                    $barcode = $today->format('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

                    // Aynı ISBN'li kitabın rafını kontrol et
                    $existingShelf = Stock::whereHas('book', function($query) use ($book) {
                        $query->where('isbn', $book->isbn);
                    })->first();

                    if ($existingShelf) {
                        // Aynı ISBN'li kitabın rafında yer var mı kontrol et
                        $stockCount = Stock::where('shelf_id', $existingShelf->shelf_id)->count();
                        if ($stockCount < 10) {
                            $shelf = Shelf::find($existingShelf->shelf_id);
                        } else {
                            // Uygun rafları bul (aynı rafta aynı kitaptan 10'dan az olanlar)
                            $availableShelves = $shelves->filter(function($shelf) use ($book) {
                                $count = Stock::where('book_id', $book->id)->where('shelf_id', $shelf->id)->count();
                                return $count < 10;
                            });
                            // Daha önce bu kitap için kullandığımız rafları öncelikle hariç tut
                            $availableShelves = $availableShelves->whereNotIn('id', $usedShelves);
                            // Eğer hiç uygun raf kalmadıysa, tüm raflar içinden tekrar seç (ama yine 10'dan fazla olmasın)
                            if ($availableShelves->isEmpty()) {
                                $availableShelves = $shelves->filter(function($shelf) use ($book) {
                                    $count = Stock::where('book_id', $book->id)->where('shelf_id', $shelf->id)->count();
                                    return $count < 10;
                                });
                            }
                            // Uygun bir raf seç
                            $shelf = $availableShelves->random();
                        }
                    } else {
                        // Uygun rafları bul (aynı rafta aynı kitaptan 10'dan az olanlar)
                        $availableShelves = $shelves->filter(function($shelf) use ($book) {
                            $count = Stock::where('book_id', $book->id)->where('shelf_id', $shelf->id)->count();
                            return $count < 10;
                        });
                        // Daha önce bu kitap için kullandığımız rafları öncelikle hariç tut
                        $availableShelves = $availableShelves->whereNotIn('id', $usedShelves);
                        // Eğer hiç uygun raf kalmadıysa, tüm raflar içinden tekrar seç (ama yine 10'dan fazla olmasın)
                        if ($availableShelves->isEmpty()) {
                            $availableShelves = $shelves->filter(function($shelf) use ($book) {
                                $count = Stock::where('book_id', $book->id)->where('shelf_id', $shelf->id)->count();
                                return $count < 10;
                            });
                        }
                        // Uygun bir raf seç
                        $shelf = $availableShelves->random();
                    }
                    $usedShelves[] = $shelf->id;

                    // Edinim kaynağı, fiyat, tarih, durum
                    $source = $sources->random();
                    $price = $source->name === 'Bağış' ? 0 : rand(50, 200);
                    $date = $today->copy()->subDays(rand(0, 365));
                    $status = 'available';

                    Stock::create([
                        'barcode' => $barcode,
                        'book_id' => $book->id,
                        'shelf_id' => $shelf->id,
                        'acquisition_source_id' => $source->id,
                        'acquisition_price' => $price,
                        'acquisition_date' => $date,
                        'status' => $status
                    ]);
                }
            }
            $this->command->info('Kitap stokları projedeki mantığa uygun şekilde başarıyla oluşturuldu.');
        });
    }
} 
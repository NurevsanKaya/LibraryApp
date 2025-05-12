<?php

namespace Database\Seeders;

use App\Models\Shelf;
use App\Models\Bookshelf;
use Illuminate\Database\Seeder;

class ShelfSeeder extends Seeder
{
    public function run(): void
    {
        // Her kitaplıkta 5 raf
        $bookshelves = Bookshelf::all();

        foreach ($bookshelves as $bookshelf) {
            for ($shelfNumber = 1; $shelfNumber <= 5; $shelfNumber++) {
                Shelf::create([
                    'shelf_number' => $shelfNumber,
                    'bookshelf_id' => $bookshelf->id
                ]);
            }
        }

        $this->command->info('240 raf başarıyla eklendi.');
    }
} 
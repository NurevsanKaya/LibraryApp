<?php

namespace Database\Seeders;

use App\Models\Bookshelf;
use App\Models\Location;
use App\Models\Categories;
use App\Models\Genres;
use Illuminate\Database\Seeder;

class BookshelfSeeder extends Seeder
{
    public function run(): void
    {
        // Her odada 4 kitaplık
        $locations = Location::all();
        $categories = Categories::all();
        $genres = Genres::all();

        foreach ($locations as $location) {
            for ($bookshelfNumber = 1; $bookshelfNumber <= 4; $bookshelfNumber++) {
                // Her kitaplığa rastgele bir kategori ve tür atayalım
                $category = $categories->random();
                $genre = $genres->random();

                Bookshelf::create([
                    'bookshelf_number' => $bookshelfNumber,
                    'category_id' => $category->id,
                    'genre_id' => $genre->id,
                    'location_id' => $location->id
                ]);
            }
        }

        $this->command->info('48 kitaplık başarıyla eklendi.');
    }
} 
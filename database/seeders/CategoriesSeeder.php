<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kitap kategorileri - Ana kategoriler (Türlerden farklı olarak)
        $categories = [
            ['name' => 'Kurgu / Roman'],
            ['name' => 'Kurgu Dışı / Bilgi'],
            ['name' => 'Çocuk ve Gençlik'],
            ['name' => 'Akademik / Ders Kitapları'],
            ['name' => 'Referans / Başvuru'],
            ['name' => 'Süreli Yayınlar'],
            
        ];

        foreach ($categories as $categoryData) {
            Categories::firstOrCreate($categoryData);
        }
    }
} 
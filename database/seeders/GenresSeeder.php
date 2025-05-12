<?php

namespace Database\Seeders;

use App\Models\Genres;
use Illuminate\Database\Seeder;

class GenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 25 kitap türü
        $genres = [
            ['name' => 'Roman'],
            ['name' => 'Öykü'],
            ['name' => 'Şiir'],
            ['name' => 'Deneme'],
            ['name' => 'Anı/Hatıra'],
            ['name' => 'Biyografi/Otobiyografi'],
            ['name' => 'Tarih'],
            ['name' => 'Felsefe'],
            ['name' => 'Psikoloji'],
            ['name' => 'Sosyoloji'],
            ['name' => 'Bilim'],
            ['name' => 'Popüler Bilim'],
            ['name' => 'Siyaset'],
            ['name' => 'İktisat/Ekonomi'],
            ['name' => 'Sanat/Sinema'],
            ['name' => 'Polisiye/Gerilim'],
            ['name' => 'Fantastik'],
            ['name' => 'Bilim Kurgu'],
            ['name' => 'Macera'],
            ['name' => 'Gezi/Seyahat'],
            ['name' => 'Kişisel Gelişim'],
            ['name' => 'Çocuk Kitapları'],
            ['name' => 'Gençlik Kitapları'],
            ['name' => 'Eğitim'],
            ['name' => 'Mizah']
        ];

        foreach ($genres as $genreData) {
            Genres::firstOrCreate($genreData);
        }
    }
} 
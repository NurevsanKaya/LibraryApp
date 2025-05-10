<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 30 gerçek Türk yayınevi
        $publishers = [
            ['name' => 'Yapı Kredi Yayınları'],
            ['name' => 'Can Yayınları'],
            ['name' => 'İş Bankası Kültür Yayınları'],
            ['name' => 'Doğan Kitap'],
            ['name' => 'İletişim Yayınları'],
            ['name' => 'Everest Yayınları'],
            ['name' => 'Alfa Yayınları'],
            ['name' => 'Remzi Kitabevi'],
            ['name' => 'Metis Yayınları'],
            ['name' => 'Sel Yayıncılık'],
            ['name' => 'Destek Yayınları'],
            ['name' => 'İthaki Yayınları'],
            ['name' => 'Pegasus Yayınları'],
            ['name' => 'Kırmızı Kedi'],
            ['name' => 'Altın Kitaplar'],
            ['name' => 'Bilgi Yayınevi'],
            ['name' => 'Say Yayınları'],
            ['name' => 'Ayrıntı Yayınları'],
            ['name' => 'Timaş Yayınları'],
            ['name' => 'Kapı Yayınları'],
            ['name' => 'Domingo Yayınevi'],
            ['name' => 'Turkuvaz Kitap'],
            ['name' => 'Epsilon Yayınevi'],
            ['name' => 'Ötüken Neşriyat'],
            ['name' => 'Pan Yayıncılık'],
            ['name' => 'Olimpos Yayınları'],
            ['name' => 'Beyaz Balina Yayınları'],
            ['name' => 'April Yayıncılık'],
            ['name' => 'Martı Yayınları'],
            ['name' => 'Kopernik Kitap']
        ];

        foreach ($publishers as $publisherData) {
            Publisher::firstOrCreate($publisherData);
        }
    }
} 
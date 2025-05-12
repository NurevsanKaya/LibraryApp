<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Yazarlar
        $authors = [
            ['first_name' => 'Orhan', 'last_name' => 'Pamuk'],
            ['first_name' => 'Yaşar', 'last_name' => 'Kemal'],
            ['first_name' => 'Sabahattin', 'last_name' => 'Ali'],
            ['first_name' => 'Elif', 'last_name' => 'Şafak'],
            ['first_name' => 'Ahmet', 'last_name' => 'Ümit'],
            ['first_name' => 'Zülfü', 'last_name' => 'Livaneli'],
            ['first_name' => 'Ayşe', 'last_name' => 'Kulin'],
            ['first_name' => 'Oğuz', 'last_name' => 'Atay'],
            ['first_name' => 'Peyami', 'last_name' => 'Safa'],
            ['first_name' => 'İlber', 'last_name' => 'Ortaylı'],
            ['first_name' => 'Yuval Noah', 'last_name' => 'Harari'],
            ['first_name' => 'George', 'last_name' => 'Orwell'],
            ['first_name' => 'J.K.', 'last_name' => 'Rowling'],
            ['first_name' => 'Fyodor', 'last_name' => 'Dostoyevski'],
            ['first_name' => 'Albert', 'last_name' => 'Camus']
        ];

        foreach ($authors as $authorData) {
            Author::firstOrCreate($authorData);
        }

        $this->command->info('15 yazar başarıyla eklendi.');
    }
}

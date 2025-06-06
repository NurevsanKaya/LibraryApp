<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default test user - role_id ekledim
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => 2, // Admin rolü
            'is_active' => 1,
        ]);

        // Call seeder classes
        $this->call([
            UserSeeder::class,
            PublisherSeeder::class,
            GenresSeeder::class,
            CategoriesSeeder::class,
            AuthorSeeder::class,
            AcquisitionSourceSeeder::class,
            BookSeeder::class,
            LocationSeeder::class,
            BookshelfSeeder::class,
            ShelfSeeder::class,
            StockSeeder::class,
            BorrowingSeeder::class,
        ]);
    }
}

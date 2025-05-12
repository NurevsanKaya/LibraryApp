<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 10 kullanıcı oluştur
        $users = [
            [
                'name' => 'Ahmet Yılmaz',
                'email' => 'ahmet.yilmaz@example.com',
                'phone' => '5321234567',
                'role_id' => 2, // Normal kullanıcı
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Ayşe Demir',
                'email' => 'ayse.demir@example.com',
                'phone' => '5331234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Mehmet Kaya',
                'email' => 'mehmet.kaya@example.com',
                'phone' => '5341234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Zeynep Şahin',
                'email' => 'zeynep.sahin@example.com',
                'phone' => '5351234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Ali Türkoğlu',
                'email' => 'ali.turkoglu@example.com',
                'phone' => '5361234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Fatma Yıldız',
                'email' => 'fatma.yildiz@example.com',
                'phone' => '5371234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Mustafa Çelik',
                'email' => 'mustafa.celik@example.com',
                'phone' => '5381234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Elif Arslan',
                'email' => 'elif.arslan@example.com',
                'phone' => '5391234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Hüseyin Güneş',
                'email' => 'huseyin.gunes@example.com',
                'phone' => '5301234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Hatice Yılmazer',
                'email' => 'hatice.yilmazer@example.com',
                'phone' => '5311234567',
                'role_id' => 2,
                'is_active' => 1,
                'password' => Hash::make('password123')
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
} 
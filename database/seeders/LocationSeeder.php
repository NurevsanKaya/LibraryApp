<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // 2 bina, her binada 2 kat, her katta 3 oda
        for ($building = 1; $building <= 2; $building++) {
            for ($floor = 1; $floor <= 2; $floor++) {
                for ($room = 1; $room <= 3; $room++) {
                    Location::create([
                        'building_number' => $building,
                        'floor_number' => $floor,
                        'room_number' => $room
                    ]);
                }
            }
        }

        $this->command->info('12 lokasyon başarıyla eklendi.');
    }
} 
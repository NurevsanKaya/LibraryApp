<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcquisitionSource;

class AcquisitionSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            ['name' => 'Satın Alma'],
            ['name' => 'Bağış'],
            ['name' => 'Değişim'],
            ['name' => 'Kurum İçi Transfer'],
            ['name' => 'Diğer']
        ];

        foreach ($sources as $source) {
            AcquisitionSource::firstOrCreate($source);
        }

        $this->command->info('5 edinme kaynağı başarıyla eklendi.');
    }
}
 
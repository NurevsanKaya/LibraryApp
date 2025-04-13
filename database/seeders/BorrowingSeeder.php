<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stock;
use App\Models\User;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Önce en az bir kullanıcımız olduğundan emin olalım
            $user = User::firstOrCreate(
                ['email' => 'member@example.com'],
                [
                    'name' => 'Test Üye',
                    'password' => bcrypt('password'),
                    'role_id' => 2, // Üye rolü
                    'is_active' => 1,
                ]
            );
            
            // Status = available olan stokları al
            $availableStocks = Stock::where('status', 'available')->get();
            
            if ($availableStocks->isEmpty()) {
                $this->command->error('Ödünç verilebilecek müsait stok bulunamadı!');
                return;
            }
            
            // Rastgele 1-2 stok seç ve ödünç ver
            $borrowCount = min(rand(1, 2), $availableStocks->count());
            $selectedStocks = $availableStocks->random($borrowCount);
            
            foreach ($selectedStocks as $stock) {
                // Ödünç verme tarihi için bugünden 1-30 gün öncesi
                $borrowDate = Carbon::now()->subDays(rand(1, 30))->format('Y-m-d');
                // Teslim tarihi ödünç verme tarihinden 15 gün sonrası
                $dueDate = Carbon::parse($borrowDate)->addDays(15)->format('Y-m-d');
                
                // Eğer bu stock daha önce ödünç verilmemişse
                if (!Borrowing::where('stock_id', $stock->id)->whereNull('return_date')->exists()) {
                    // Stok durumunu güncelle
                    $stock->update(['status' => 'borrowed']);
                    
                    // Ödünç kaydını oluştur - status alanında 'active' kullan
                    Borrowing::create([
                        'user_id' => $user->id,
                        'stock_id' => $stock->id,
                        'borrow_date' => $borrowDate,
                        'due_date' => $dueDate,
                        'status' => 'active',
                    ]);
                }
            }
            
            $this->command->info("$borrowCount adet kitap ödünç verildi.");
        });
    }
} 
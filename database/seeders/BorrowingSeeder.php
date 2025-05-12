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
            // Önce borrowings tablosunu temizle
            DB::table('borrowings')->truncate();

            $users = User::where('role_id', 2)->get(); // Sadece üye rolündeki kullanıcılar
            $stocks = Stock::where('status', 'available')->get();
            $today = Carbon::now();

            foreach ($users as $user) {
                // Her kullanıcı için 1-3 adet ödünç kaydı oluştur
                $borrowCount = rand(1, 3);
                $usedStocks = [];

                for ($i = 0; $i < $borrowCount; $i++) {
                    // Kullanılmamış bir stok seç
                    $availableStocks = $stocks->whereNotIn('id', $usedStocks);
                    if ($availableStocks->isEmpty()) {
                        break;
                    }
                    $stock = $availableStocks->random();
                    $usedStocks[] = $stock->id;

                    // Ödünç alma tarihi (son 1 yıl içinde rastgele bir tarih)
                    $borrowDate = $today->copy()->subDays(rand(0, 365));
                    
                    // Teslim tarihi (ödünç alma tarihinden 15 gün sonra)
                    $dueDate = $borrowDate->copy()->addDays(15);

                    // Durum belirleme
                    $status = rand(0, 2); // 0: iade edilmiş, 1: aktif, 2: gecikmiş
                    
                    if ($status === 0) {
                        // İade edilmiş
                        $returnDate = $dueDate->copy()->subDays(rand(0, 5)); // Teslim tarihinden önce iade
                        $status = 'completed';
                    } elseif ($status === 1) {
                        // Aktif
                        $returnDate = null;
                        $status = 'active';
                    } else {
                        // Gecikmiş
                        $returnDate = null;
                        $status = 'active';
                        $dueDate = $borrowDate->copy()->addDays(5); // 5 günlük süre ver
                    }

                    Borrowing::create([
                        'user_id' => $user->id,
                        'stock_id' => $stock->id,
                        'borrow_date' => $borrowDate,
                        'due_date' => $dueDate,
                        'return_date' => $returnDate,
                        'status' => $status
                    ]);

                    // Stok durumunu güncelle
                    $stock->update(['status' => 'borrowed']);
                }
            }

            $this->command->info('Ödünç kayıtları başarıyla oluşturuldu.');
        });
    }
} 
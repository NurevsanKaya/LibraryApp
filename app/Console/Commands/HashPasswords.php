<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HashPasswords extends Command
{
    protected $signature = 'hash:passwords';
    protected $description = 'Hash plain-text passwords in the database';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            if (!password_get_info($user->password)['algo']) { // Şifre hashlenmemişse
                $user->password = Hash::make($user->password);
                $user->save();
                $this->info("Password hashed for user: {$user->email}");
            }
        }

        $this->info('All plain-text passwords have been hashed.');
    }
}

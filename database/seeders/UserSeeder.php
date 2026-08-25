<?php

namespace Database\Seeders;

use App\Models\Tingkat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $tingkatSMP = Tingkat::where('nama', 'SMP')->first();
        $tingkatSMA = Tingkat::where('nama', 'SMA')->first();

        $users = [
            [
                'nama' => 'Administrator',
                'email' => 'admin@pesantren.test',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'tingkat_id' => null,
                'no_hp' => '081234567890',
            ],
            [
                'nama' => 'Bendahara SMP',
                'email' => 'bendahara.smp@pesantren.test',
                'password' => Hash::make('password123'),
                'role' => 'bendahara',
                'tingkat_id' => $tingkatSMP->id,
                'no_hp' => '081234567891',
            ],
            [
                'nama' => 'Bendahara SMA',
                'email' => 'bendahara.sma@pesantren.test',
                'password' => Hash::make('password123'),
                'role' => 'bendahara',
                'tingkat_id' => $tingkatSMA->id,
                'no_hp' => '081234567892',
            ],
            [
                'nama' => 'Pimpinan Yayasan',
                'email' => 'pimpinan@pesantren.test',
                'password' => Hash::make('password123'),
                'role' => 'pimpinan',
                'tingkat_id' => null,
                'no_hp' => '081234567893',
            ],
            [
                'nama' => 'Orang Tua Santri',
                'email' => 'ortu@pesantren.test',
                'password' => Hash::make('password123'),
                'role' => 'ortu',
                'tingkat_id' => null,
                'no_hp' => '081234567894',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info('UserSeeder: Data user berhasil ditambahkan!');
        $this->command->info('===============================================');
        $this->command->info('Akun untuk login:');
        $this->command->info('Admin     : admin@pesantren.test / password123');
        $this->command->info('Bendahara SMP: bendahara.smp@pesantren.test / password123');
        $this->command->info('Bendahara SMA: bendahara.sma@pesantren.test / password123');
        $this->command->info('Pimpinan  : pimpinan@pesantren.test / password123');
        $this->command->info('Orang Tua : ortu@pesantren.test / password123');
        $this->command->info('===============================================');
    }
}
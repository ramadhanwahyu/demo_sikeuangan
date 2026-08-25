<?php

namespace Database\Seeders;

use App\Models\Tingkat;
use Illuminate\Database\Seeder;

class TingkatSeeder extends Seeder
{
    public function run(): void
    {
        $tingkat = [
            [
                'nama' => 'SMP',
            ],
            [
                'nama' => 'SMA',
            ],
        ];

        foreach ($tingkat as $data) {
            Tingkat::firstOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }

        $this->command->info('TingkatSeeder: Data tingkat berhasil ditambahkan!');
    }
}
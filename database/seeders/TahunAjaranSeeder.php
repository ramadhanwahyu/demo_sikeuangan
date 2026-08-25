<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = [
            [
                'nama' => '2025/2026',
                'is_active' => true,
            ],
            [
                'nama' => '2026/2027',
                'is_active' => false,
            ],
        ];

        foreach ($tahunAjaran as $data) {
            TahunAjaran::firstOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }

        $this->command->info('TahunAjaranSeeder: Data tahun ajaran berhasil ditambahkan!');
    }
}
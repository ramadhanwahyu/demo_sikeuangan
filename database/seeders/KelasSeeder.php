<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Tingkat;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $tingkatSMP = Tingkat::where('nama', 'SMP')->first();
        $tingkatSMA = Tingkat::where('nama', 'SMA')->first();

        // Kelas SMP (Kelas 7, 8, 9)
        $kelasSMP = [
            ['nama' => 'VII A', 'urutan' => 1],
            ['nama' => 'VII B', 'urutan' => 1],
            ['nama' => 'VIII A', 'urutan' => 2],
            ['nama' => 'VIII B', 'urutan' => 2],
            ['nama' => 'IX A', 'urutan' => 3],
            ['nama' => 'IX B', 'urutan' => 3],
        ];

        // Kelas SMA (Kelas 10, 11, 12)
        $kelasSMA = [
            ['nama' => 'X', 'urutan' => 1],
            ['nama' => 'XI PPL', 'urutan' => 2],
            ['nama' => 'XI TKJ', 'urutan' => 2],
            ['nama' => 'XII PPL', 'urutan' => 3],
            ['nama' => 'XII TKJ', 'urutan' => 3],
        ];

        // Insert kelas SMP
        foreach ($kelasSMP as $kelas) {
            Kelas::firstOrCreate(
                [
                    'tingkat_id' => $tingkatSMP->id,
                    'nama' => $kelas['nama'],
                ],
                [
                    'tingkat_id' => $tingkatSMP->id,
                    'nama' => $kelas['nama'],
                    'urutan' => $kelas['urutan'],
                ]
            );
        }

        // Insert kelas SMA
        foreach ($kelasSMA as $kelas) {
            Kelas::firstOrCreate(
                [
                    'tingkat_id' => $tingkatSMA->id,
                    'nama' => $kelas['nama'],
                ],
                [
                    'tingkat_id' => $tingkatSMA->id,
                    'nama' => $kelas['nama'],
                    'urutan' => $kelas['urutan'],
                ]
            );
        }

        $this->command->info('KelasSeeder: Data kelas berhasil ditambahkan!');
    }
}
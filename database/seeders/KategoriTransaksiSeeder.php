<?php

namespace Database\Seeders;

use App\Models\KategoriTransaksi;
use Illuminate\Database\Seeder;

class KategoriTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriPemasukan = [
            [
                'nama' => 'SPP',
                'jenis' => 'pemasukan',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'Uang Pendaftaran',
                'jenis' => 'pemasukan',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'Uang Ujian Akhir/Kelulusan',
                'jenis' => 'pemasukan',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'Pemasukan Lainnya',
                'jenis' => 'pemasukan',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'Uang Jajan Santri',
                'jenis' => 'pemasukan',
                'is_uang_jajan' => true,
                'is_sistem' => true,
            ],
        ];

        $kategoriPengeluaran = [
            [
                'nama' => 'Gaji Guru',
                'jenis' => 'pengeluaran',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'ATK (Alat Tulis Kantor)',
                'jenis' => 'pengeluaran',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'Listrik',
                'jenis' => 'pengeluaran',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'WiFi',
                'jenis' => 'pengeluaran',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'PDAM',
                'jenis' => 'pengeluaran',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'Keperluan Dapur',
                'jenis' => 'pengeluaran',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
            [
                'nama' => 'Uang Jajan Santri',
                'jenis' => 'pengeluaran',
                'is_uang_jajan' => true,
                'is_sistem' => true,
            ],
            [
                'nama' => 'Lainnya',
                'jenis' => 'pengeluaran',
                'is_uang_jajan' => false,
                'is_sistem' => true,
            ],
        ];

        // Insert kategori pemasukan
        foreach ($kategoriPemasukan as $kategori) {
            KategoriTransaksi::firstOrCreate(
                [
                    'nama' => $kategori['nama'],
                    'jenis' => $kategori['jenis'],
                ],
                $kategori
            );
        }

        // Insert kategori pengeluaran
        foreach ($kategoriPengeluaran as $kategori) {
            KategoriTransaksi::firstOrCreate(
                [
                    'nama' => $kategori['nama'],
                    'jenis' => $kategori['jenis'],
                ],
                $kategori
            );
        }

        $this->command->info('KategoriTransaksiSeeder: Data kategori berhasil ditambahkan!');
    }
}
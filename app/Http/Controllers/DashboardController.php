<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\SppBill;
use App\Models\TransaksiKas;
use App\Models\TahunAjaran;
use App\Models\Tingkat;
use App\Models\User;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard sesuai role user
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        return match ($role) {
            'admin' => $this->dashboardAdmin(),
            'bendahara' => $this->dashboardBendahara($user),
            'pimpinan' => $this->dashboardPimpinan(),
            'ortu' => $this->dashboardOrtu($user),
            default => redirect()->route('login'),
        };
    }

    /**
     * Dashboard untuk admin
     */
    private function dashboardAdmin()
    {
        $data = [
            'totalSantri' => Santri::count(),
            'totalBendahara' => User::where('role', 'bendahara')->count(),
            'totalTingkat' => Tingkat::count(),
            'totalTahunAjaranAktif' => TahunAjaran::where('is_active', true)->first()?->nama ?? '-',
        ];

        return view('dashboard.admin', $data);
    }

    /**
     * Dashboard untuk bendahara (SMP/SMA)
     */
    private function dashboardBendahara($user)
    {
        $tingkatId = $user->tingkat_id;
        $tingkat = Tingkat::find($tingkatId);
        $tingkatNama = $tingkat?->nama ?? 'Tidak Ada Tingkat';

        // Hitung saldo kas umum
        $totalPemasukanUmum = TransaksiKas::where('tingkat_id', $tingkatId)
            ->where('jenis', 'pemasukan')
            ->whereHas('kategori', fn($q) => $q->where('is_uang_jajan', false))
            ->sum('jumlah');

        $totalPengeluaranUmum = TransaksiKas::where('tingkat_id', $tingkatId)
            ->where('jenis', 'pengeluaran')
            ->whereHas('kategori', fn($q) => $q->where('is_uang_jajan', false))
            ->sum('jumlah');

        $saldoKasUmum = $totalPemasukanUmum - $totalPengeluaranUmum;

        // Hitung total saldo uang jajan seluruh santri di tingkat ini
        $saldoUangJajan = Santri::where('tingkat_id', $tingkatId)
            ->sum('saldo_uang_jajan');

        // Hitung total santri aktif
        $totalSantriAktif = Santri::where('tingkat_id', $tingkatId)
            ->where('status', 'aktif')
            ->count();

        // Hitung tagihan SPP belum lunas
        $totalTagihanSppBelumLunas = SppBill::whereHas('santri', function ($q) use ($tingkatId) {
                $q->where('tingkat_id', $tingkatId);
            })
            ->where('status', 'belum')
            ->count();

        // Transaksi terbaru (5 terakhir)
        $transaksiTerbaru = TransaksiKas::with(['kategori', 'santri'])
            ->where('tingkat_id', $tingkatId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $data = [
            'tingkatNama' => $tingkatNama,
            'tingkatId' => $tingkatId,
            'totalSantriAktif' => $totalSantriAktif,
            'saldoKasUmum' => $saldoKasUmum,
            'saldoUangJajan' => $saldoUangJajan,
            'totalTagihanSppBelumLunas' => $totalTagihanSppBelumLunas,
            'transaksiTerbaru' => $transaksiTerbaru,
            'totalPemasukanUmum' => $totalPemasukanUmum,
            'totalPengeluaranUmum' => $totalPengeluaranUmum,
        ];

        return view('dashboard.bendahara', $data);
    }

    /**
     * Dashboard untuk pimpinan yayasan
     */
    private function dashboardPimpinan()
    {
        $data = [
            'totalSantri' => Santri::count(),
            'totalPemasukan' => TransaksiKas::where('jenis', 'pemasukan')->sum('jumlah'),
            'totalPengeluaran' => TransaksiKas::where('jenis', 'pengeluaran')->sum('jumlah'),
            'saldoKasSMP' => $this->hitungSaldoKasByTingkat('SMP'),
            'saldoKasSMA' => $this->hitungSaldoKasByTingkat('SMA'),
        ];

        return view('dashboard.pimpinan', $data);
    }

    /**
     * Dashboard untuk orang tua santri
     */
    private function dashboardOrtu($user)
    {
        $santriIds = $user->waliSantri()->pluck('santri_id');
        $santriList = Santri::whereIn('id', $santriIds)->get();

        $data = [
            'santriList' => $santriList,
        ];

        return view('dashboard.ortu', $data);
    }

    /**
     * Hitung saldo kas per tingkat (untuk bendahara)
     */
    private function hitungSaldoKas($tingkatId)
    {
        $pemasukan = TransaksiKas::where('tingkat_id', $tingkatId)
                        ->where('jenis', 'pemasukan')
                        ->sum('jumlah');
        $pengeluaran = TransaksiKas::where('tingkat_id', $tingkatId)
                        ->where('jenis', 'pengeluaran')
                        ->sum('jumlah');
        return $pemasukan - $pengeluaran;
    }

    /**
     * Hitung saldo kas per tingkat berdasarkan nama tingkat (untuk pimpinan)
     */
    private function hitungSaldoKasByTingkat($tingkatNama)
    {
        $tingkat = Tingkat::where('nama', $tingkatNama)->first();
        if (!$tingkat) {
            return 0;
        }
        return $this->hitungSaldoKas($tingkat->id);
    }
}
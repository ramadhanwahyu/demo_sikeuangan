@extends('layouts.app')

@section('title', 'Dashboard Bendahara ' . $tingkatNama)

@section('content')
<div class="space-y-6">
    
    <!-- Clean Page Header (Bukan Banner Gradasi AI) -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-xl font-bold text-slate-900">Dashboard Bendahara</h1>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-emerald-200">
                    Tingkat {{ $tingkatNama }}
                </span>
            </div>
            <p class="text-slate-500 text-xs mt-1">Ringkasan kas, unit sekolah, dan transaksi operasional terbaru.</p>
        </div>
        <div>
            <a href="{{ route('transaksi.create') }}" class="inline-flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Tambah Transaksi</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Santri Aktif -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Santri Aktif</span>
                    <span class="p-1.5 bg-slate-100 text-slate-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold text-slate-900">{{ $totalSantriAktif }}</span>
                    <span class="text-xs text-slate-400">Santri</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('santri.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 inline-flex items-center space-x-1">
                    <span>Kelola Santri</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Saldo Kas Umum (Ukuran Font Disesuaikan Agar Tidak Terpotong) -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo Kas Umum</span>
                    <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="text-xl lg:text-2xl font-bold tracking-tight whitespace-nowrap {{ $saldoKasUmum >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        Rp {{ number_format($saldoKasUmum, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('transaksi.index', ['tipe' => 'umum']) }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 inline-flex items-center space-x-1">
                    <span>Lihat Kas Umum</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Saldo Uang Jajan -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Uang Jajan Santri</span>
                    <span class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                </div>
                <div class="mt-3">
                    <span class="text-xl lg:text-2xl font-bold text-blue-600 tracking-tight whitespace-nowrap">
                        Rp {{ number_format($saldoUangJajan, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('uang-jajan.saldo') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 inline-flex items-center space-x-1">
                    <span>Kelola Saldo</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Tagihan SPP Belum Lunas -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Tunggakan SPP</span>
                    <span class="p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <span class="text-2xl font-bold text-amber-600">{{ $totalTagihanSppBelumLunas }}</span>
                    <span class="text-xs text-slate-400">Tagihan</span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <span class="text-xs text-slate-400 flex items-center space-x-1">
                    <span>Modul SPP (Segera)</span>
                </span>
            </div>
        </div>

    </div>

    <!-- Shortcut Cepat -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Aksi Cepat</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('transaksi.create') }}" 
               class="p-3 rounded-lg border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition flex items-center space-x-3">
                <div class="p-2 bg-emerald-100 text-emerald-700 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-slate-800">Catat Transaksi</span>
                    <span class="text-[10px] text-slate-400">Kas Umum</span>
                </div>
            </a>

            <a href="{{ route('uang-jajan.saldo') }}" 
               class="p-3 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50/30 transition flex items-center space-x-3">
                <div class="p-2 bg-blue-100 text-blue-700 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-slate-800">Uang Jajan</span>
                    <span class="text-[10px] text-slate-400">Setor/Tarik</span>
                </div>
            </a>

            <a href="{{ route('santri.create') }}" 
               class="p-3 rounded-lg border border-slate-200 hover:border-amber-500 hover:bg-amber-50/30 transition flex items-center space-x-3">
                <div class="p-2 bg-amber-100 text-amber-700 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-slate-800">Tambah Santri</span>
                    <span class="text-[10px] text-slate-400">Registrasi Baru</span>
                </div>
            </a>

            <a href="{{ route('transaksi.index', ['tipe' => 'umum']) }}" 
               class="p-3 rounded-lg border border-slate-200 hover:border-slate-400 hover:bg-slate-50 transition flex items-center space-x-3">
                <div class="p-2 bg-slate-100 text-slate-700 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-slate-800">Laporan Kas</span>
                    <span class="text-[10px] text-slate-400">Rekap Transaksi</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Tabel Transaksi Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Transaksi Terbaru</h3>
            <a href="{{ route('transaksi.index', ['tipe' => 'umum']) }}" class="text-xs font-medium text-emerald-600 hover:text-emerald-700">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Jenis</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Keterangan / Santri</th>
                        <th class="px-5 py-3 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($transaksiTerbaru as $t)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                            {{ $t->tanggal ? \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($t->jenis === 'pemasukan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-100 text-emerald-800">
                                    Pemasukan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-rose-100 text-rose-800">
                                    Pengeluaran
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-slate-800 font-medium">
                            {{ $t->kategori->nama ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-600">
                            @if($t->santri)
                                <span class="font-semibold text-slate-800">{{ $t->santri->nama_lengkap }}</span>
                            @endif
                            <span class="text-slate-500">{{ $t->keterangan ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold {{ $t->jenis === 'pemasukan' ? 'text-emerald-600' : 'text-slate-800' }} whitespace-nowrap">
                            {{ $t->jenis === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                            Belum ada transaksi tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
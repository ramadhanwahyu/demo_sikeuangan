@extends('layouts.app')

@section('title', 'Detail Uang Jajan Santri')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action & Profile Header -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 font-bold text-lg flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($santri->nama_lengkap, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">{{ $santri->nama_lengkap }}</h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                            NIS: {{ $santri->nis }}
                        </span>
                        <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                            Tingkat: {{ $santri->tingkat->nama ?? '-' }}
                        </span>
                        <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                            Kelas: {{ $santri->kelas->nama ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ route('uang-jajan.saldo') }}" class="px-3.5 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors inline-flex items-center space-x-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6">
            <!-- Total Masuk -->
            <div class="bg-emerald-50/60 border border-emerald-100 rounded-lg p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-emerald-600">Total Masuk</p>
                    <p class="text-lg font-bold text-emerald-700 mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="p-2.5 bg-emerald-100 rounded-lg text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </div>
            </div>

            <!-- Total Keluar -->
            <div class="bg-rose-50/60 border border-rose-100 rounded-lg p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-rose-600">Total Keluar</p>
                    <p class="text-lg font-bold text-rose-700 mt-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                </div>
                <div class="p-2.5 bg-rose-100 rounded-lg text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                </div>
            </div>

            <!-- Saldo -->
            <div class="bg-sky-50/60 border border-sky-100 rounded-lg p-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-sky-600">Sisa Saldo</p>
                    <p class="text-lg font-bold text-sky-700 mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                </div>
                <div class="p-2.5 bg-sky-100 rounded-lg text-sky-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800">Riwayat Transaksi Uang Jajan</h2>
            <span class="text-xs text-slate-400">Menampilkan seluruh data riwayat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] uppercase tracking-wider font-semibold text-slate-500">
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Jenis</th>
                        <th class="px-6 py-3">Jumlah</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($transaksi as $t)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-3.5 whitespace-nowrap text-slate-600">
                            {{ $t->tanggal ? \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-3.5 whitespace-nowrap">
                            @if($t->jenis === 'pemasukan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Pemasukan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                    Pengeluaran
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 whitespace-nowrap font-semibold {{ $t->jenis === 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $t->jenis === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 max-w-xs truncate text-slate-600">
                            {{ $t->keterangan ?? '-' }}
                        </td>
                        <td class="px-6 py-3.5 whitespace-nowrap text-slate-600">
                            {{ $t->user->nama ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-xs font-medium text-slate-500">Belum ada riwayat transaksi uang jajan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksi->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $transaksi->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
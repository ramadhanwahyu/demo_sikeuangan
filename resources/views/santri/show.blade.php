@extends('layouts.app')

@section('title', 'Detail Santri - ' . $santri->nama_lengkap)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header Navigation & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('santri.index') }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Detail Profil Santri</h1>
                <p class="text-xs text-slate-500 mt-0.5">Informasi lengkap data akademik, wali, dan status pembayaran.</p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('santri.edit', $santri->id) }}" class="px-3.5 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors inline-flex items-center space-x-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Data</span>
            </a>
            <a href="{{ route('santri.index') }}" class="px-3.5 py-2 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xl uppercase tracking-wider">
                    {{ substr($santri->nama_lengkap, 0, 2) }}
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-lg font-bold text-slate-900">{{ $santri->nama_lengkap }}</h2>
                        @if($santri->status === 'aktif')
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">Aktif</span>
                        @elseif($santri->status === 'lulus')
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-full">Lulus</span>
                        @else
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold text-slate-600 bg-slate-100 border border-slate-200 rounded-full">Keluar</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1">NIS: <span class="font-mono text-slate-700 font-medium">{{ $santri->nis }}</span></p>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-100 rounded-lg p-3.5 flex items-center space-x-4 min-w-[200px]">
                <div class="p-2.5 bg-emerald-500 text-white rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <span class="block text-[11px] font-medium text-slate-500">Saldo Jajan</span>
                    <span class="text-sm font-bold text-slate-900">Rp {{ number_format($santri->saldo_uang_jajan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6">
            
            <!-- Section Data Akademik & Pribadi -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Informasi Akademik & Pribadi</h3>
                <dl class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <dt class="text-slate-500">Tingkat</dt>
                        <dd class="font-medium text-slate-900">{{ $santri->tingkat->nama ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <dt class="text-slate-500">Kelas</dt>
                        <dd class="font-medium text-slate-900">{{ $santri->kelas->nama ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <dt class="text-slate-500">Tahun Masuk</dt>
                        <dd class="font-medium text-slate-900">{{ $santri->tahun_masuk }}</dd>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <dt class="text-slate-500">Tanggal Lahir</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d M Y') : '-' }}
                        </dd>
                    </div>
                    <div class="pt-1.5">
                        <dt class="text-slate-500 mb-1">Alamat Tempat Tinggal</dt>
                        <dd class="font-medium text-slate-800 leading-relaxed bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                            {{ $santri->alamat ?? 'Belum ada data alamat.' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Section Wali Santri -->
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Wali Santri Terdaftar</h3>
                @if($santri->waliSantri->count() > 0)
                    <div class="space-y-3">
                        @foreach($santri->waliSantri as $wali)
                            <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-lg flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $wali->user->nama }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Hubungan: <span class="capitalize text-slate-700 font-medium">{{ $wali->hubungan }}</span></p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">No. HP: <span class="font-mono text-slate-700">{{ $wali->user->no_hp ?? '-' }}</span></p>
                                </div>
                                <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-800 rounded">Terverifikasi</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-lg border border-dashed border-slate-200 text-center">
                        <p class="text-xs text-slate-400">Belum ada wali yang terdaftar untuk santri ini.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Riwayat Tagihan SPP Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Riwayat Tagihan SPP</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar kewajiban pembayaran SPP santri.</p>
            </div>
        </div>

        @if($santri->sppBills->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-medium">
                        <tr>
                            <th class="px-6 py-3">Tahun Ajaran</th>
                            <th class="px-6 py-3">Bulan</th>
                            <th class="px-6 py-3">Nominal</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($santri->sppBills as $bill)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3.5 font-medium text-slate-900">{{ $bill->tahunAjaran->nama ?? '-' }}</td>
                                <td class="px-6 py-3.5 capitalize">{{ $bill->bulan }}</td>
                                <td class="px-6 py-3.5 font-medium text-slate-900">Rp {{ number_format($bill->nominal, 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5">
                                    @if($bill->status === 'lunas')
                                        <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-full">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-xs text-slate-500 font-medium">Belum ada riwayat tagihan SPP.</p>
            </div>
        @endif
    </div>

</div>
@endsection
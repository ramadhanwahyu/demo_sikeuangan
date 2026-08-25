@extends('layouts.app')

@section('title', 'Saldo Uang Jajan Santri')

@section('content')
<div class="space-y-5">

    <!-- Header & Action Navigation -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <!-- Tombol Kembali ke Halaman Transaksi -->
            <a href="{{ url('/transaksi?tipe=uang_jajan') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors" title="Kembali ke Transaksi Uang Jajan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900">Saldo Uang Jajan Santri</h1>
                <p class="text-slate-500 text-xs mt-0.5">Daftar saldo dan riwayat uang saku harian seluruh santri.</p>
            </div>
        </div>
    </div>

    <!-- Main Card Content -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Filter Form Section -->
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <form method="GET" action="{{ route('uang-jajan.saldo') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS atau Nama..." 
                        class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 transition">
                </div>

                <!-- Dropdown Tingkat -->
                @if(auth()->user()->role !== 'bendahara')
                <div>
                    <select name="tingkat_id" class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 transition">
                        <option value="">Semua Tingkat</option>
                        @foreach($tingkatList as $tingkat)
                        <option value="{{ $tingkat->id }}" {{ request('tingkat_id') == $tingkat->id ? 'selected' : '' }}>
                            {{ $tingkat->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Submit Filter Button -->
                <div class="flex items-center space-x-2">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors shadow-sm inline-flex items-center justify-center space-x-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Filter Data</span>
                    </button>

                    @if(request('search') || request('tingkat_id'))
                        <a href="{{ route('uang-jajan.saldo') }}" class="px-3 py-2 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors border border-rose-200" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                        <th class="px-4 py-3">NIS</th>
                        <th class="px-4 py-3">Nama Santri</th>
                        <th class="px-4 py-3">Tingkat</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Saldo Uang Jajan</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($santriList as $s)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-3 font-mono font-medium text-slate-600 whitespace-nowrap">{{ $s->nis }}</td>
                        <td class="px-4 py-3 font-bold text-slate-800 whitespace-nowrap">{{ $s->nama_lengkap }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $s->tingkat->nama ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $s->kelas->nama ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-md">
                                Rp {{ number_format($s->saldo_uang_jajan, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <div class="inline-flex items-center space-x-1.5">
                                <!-- Detail Button -->
                                <a href="{{ route('uang-jajan.detail', $s->id) }}" 
                                   class="px-2.5 py-1 text-[11px] font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded border border-slate-200 transition-colors">
                                    Detail
                                </a>
                                <!-- Tambah Saldo Button -->
                                <a href="{{ route('uang-jajan.tambah', $s->id) }}" 
                                   class="px-2.5 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded border border-emerald-200 transition-colors">
                                    + Setor
                                </a>
                                <!-- Tarik Saldo Button -->
                                <a href="{{ route('uang-jajan.tarik', $s->id) }}" 
                                   class="px-2.5 py-1 text-[11px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded border border-blue-200 transition-colors">
                                    - Tarik
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center space-y-1">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-xs font-medium">Tidak ada data santri ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/30">
            {{ $santriList->links() }}
        </div>
    </div>
</div>
@endsection
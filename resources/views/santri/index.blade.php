@extends('layouts.app')

@section('title', 'Data Santri')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Main Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Data Santri</h1>
            <p class="text-slate-500 text-xs mt-1">Kelola data induk santri, filter berdasarkan kelas, dan pantau saldo uang jajan.</p>
        </div>
        <div>
            <a href="{{ route('santri.create') }}" class="inline-flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                <span>Tambah Santri Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('santri.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <!-- Search Field -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS atau Nama Santri..." 
                       class="w-full pl-9 pr-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400">
            </div>
            
            <!-- Tingkat Filter -->
            <div>
                <select name="tingkat_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700">
                    <option value="">Semua Tingkat</option>
                    @foreach($tingkatList as $tingkat)
                        <option value="{{ $tingkat->id }}" {{ request('tingkat_id') == $tingkat->id ? 'selected' : '' }}>
                            {{ $tingkat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kelas Filter -->
            <div>
                <select name="kelas_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium text-xs py-2 px-3 rounded-lg transition-colors flex items-center justify-center space-x-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'tingkat_id', 'kelas_id']))
                    <a href="{{ route('santri.index') }}" title="Reset Filter" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">NIS</th>
                        <th class="px-5 py-3.5">Nama Santri</th>
                        <th class="px-5 py-3.5">Tingkat</th>
                        <th class="px-5 py-3.5">Kelas</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Saldo Jajan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($santri as $s)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-slate-600 font-medium whitespace-nowrap">
                            {{ $s->nis }}
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-slate-800 whitespace-nowrap">
                            {{ $s->nama_lengkap }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                            {{ $s->tingkat->nama ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                            {{ $s->kelas->nama ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($s->status === 'aktif')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-100 text-emerald-800">
                                    Aktif
                                </span>
                            @elseif($s->status === 'lulus')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-blue-100 text-blue-800">
                                    Lulus
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                                    {{ ucfirst($s->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 font-mono font-medium text-slate-800 whitespace-nowrap">
                            Rp {{ number_format($s->saldo_uang_jajan, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1">
                                <a href="{{ route('santri.show', $s->id) }}" 
                                   title="Detail Santri"
                                   class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('santri.edit', $s->id) }}" 
                                   title="Edit Data"
                                   class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('santri.destroy', $s->id) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data santri ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            title="Hapus Santri"
                                            class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                            Tidak ada data santri yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($santri->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $santri->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
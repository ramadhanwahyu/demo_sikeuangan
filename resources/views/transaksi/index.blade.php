@extends('layouts.app')

@section('title', 'Data Transaksi Kas')

@section('content')
<div class="space-y-6">

    <!-- Header Navigation & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Transaksi Kas</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola pencatatan arus kas masuk dan keluar lembaga.</p>
        </div>

        <div class="flex items-center space-x-2">
            @if($tipe === 'uang_jajan')
                <a href="{{ route('uang-jajan.saldo') }}" class="px-3.5 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors inline-flex items-center space-x-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Saldo Santri</span>
                </a>
            @endif
            @if($tipe === 'uang_jajan')
                <a href="{{ route('uang-jajan.create') }}" class="px-3.5 py-2 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm inline-flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Uang Jajan</span>
                </a>
            @else
                <a href="{{ route('transaksi.create') }}" class="px-3.5 py-2 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm inline-flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Transaksi</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Tab Switching & Stat Cards Wrapper -->
    <div class="space-y-4">
        
        <!-- Tab Pemisahan Kas -->
        <div class="flex border-b border-slate-200 space-x-4">
            <a href="{{ route('transaksi.index', ['tipe' => 'umum']) }}" 
               class="pb-2.5 text-xs font-semibold border-b-2 transition-colors inline-flex items-center space-x-2 {{ $tipe === 'umum' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span>Kas Umum</span>
            </a>
            <a href="{{ route('transaksi.index', ['tipe' => 'uang_jajan']) }}" 
               class="pb-2.5 text-xs font-semibold border-b-2 transition-colors inline-flex items-center space-x-2 {{ $tipe === 'uang_jajan' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Uang Jajan Santri</span>
            </a>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Pemasukan -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Pemasukan</span>
                    <span class="text-lg font-bold text-emerald-600 mt-1 block">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                </div>
            </div>

            <!-- Pengeluaran -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Pengeluaran</span>
                    <span class="text-lg font-bold text-rose-600 mt-1 block">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                </div>
                <div class="p-2.5 bg-rose-50 rounded-lg text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                </div>
            </div>

            <!-- Saldo -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Saldo Akhir</span>
                    <span class="text-lg font-bold text-slate-900 mt-1 block">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('transaksi.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3">
            <input type="hidden" name="tipe" value="{{ $tipe }}">
            
            <div>
                <input type="date" name="dari" value="{{ request('dari') }}" 
                       class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800" placeholder="Dari Tanggal">
            </div>

            <div>
                <input type="date" name="sampai" value="{{ request('sampai') }}" 
                       class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800" placeholder="Sampai Tanggal">
            </div>

            <div>
                <select name="jenis" class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800">
                    <option value="">Semua Jenis</option>
                    <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <div>
                <select name="kategori_id" class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kategori)
                        @if($tipe === 'umum' && !$kategori->is_uang_jajan)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }} ({{ $kategori->jenis }})
                            </option>
                        @elseif($tipe === 'uang_jajan' && $kategori->is_uang_jajan)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }} ({{ $kategori->jenis }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            @if(auth()->user()->role !== 'bendahara')
            <div>
                <select name="tingkat_id" class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800">
                    <option value="">Semua Tingkat</option>
                    @foreach($tingkatList as $tingkat)
                        <option value="{{ $tingkat->id }}" {{ request('tingkat_id') == $tingkat->id ? 'selected' : '' }}>
                            {{ $tingkat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium text-white bg-slate-800 hover:bg-slate-900 rounded-lg transition-colors shadow-sm">
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-medium uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Jenis</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Santri</th>
                        <th class="px-5 py-3">Tingkat</th>
                        <th class="px-5 py-3">Jumlah</th>
                        <th class="px-5 py-3">Keterangan</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksi as $t)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-slate-900 whitespace-nowrap">
                            {{ $t->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($t->jenis === 'pemasukan')
                                <span class="px-2 py-0.5 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                    Pemasukan
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-[11px] font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-full">
                                    Pengeluaran
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 font-medium text-slate-800">
                            {{ $t->kategori->nama ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5">
                            {{ $t->santri->nama_lengkap ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5">
                            {{ $t->tingkat->nama ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 font-semibold whitespace-nowrap {{ $t->jenis === 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $t->jenis === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate">
                            {{ $t->keterangan ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('transaksi.show', $t->id) }}" class="p-1 text-slate-400 hover:text-slate-700 transition-colors" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('transaksi.edit', $t->id) }}" class="p-1 text-slate-400 hover:text-amber-600 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400">
                            <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"></path></svg>
                            <p class="text-xs font-medium">Tidak ada data transaksi yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksi->hasPages())
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
            {{ $transaksi->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
@extends('layouts.app')

@section('title', 'Tambah Santri Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Navigation & Title -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('santri.index') }}" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Tambah Santri Baru</h1>
                <p class="text-xs text-slate-500 mt-0.5">Isi formulir berikut untuk menambahkan data induk santri baru.</p>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <form action="{{ route('santri.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf

        <div class="p-6 space-y-6">
            
            <!-- Section 1: Identitas Pribadi -->
            <div>
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Informasi Identitas</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- NIS -->
                    <div>
                        <label for="nis" class="block text-xs font-semibold text-slate-700 mb-1">NIS <span class="text-rose-500">*</span></label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required placeholder="Contoh: 2024001"
                               class="w-full px-3 py-2 text-xs border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('nis') border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-emerald-500 @enderror text-slate-800 placeholder-slate-400">
                        @error('nis') 
                            <p class="text-rose-500 text-[11px] mt-1 flex items-center space-x-1">
                                <span>{{ $message }}</span>
                            </p> 
                        @enderror
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Masukkan nama lengkap santri"
                               class="w-full px-3 py-2 text-xs border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('nama_lengkap') border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-emerald-500 @enderror text-slate-800 placeholder-slate-400">
                        @error('nama_lengkap') 
                            <p class="text-rose-500 text-[11px] mt-1 flex items-center space-x-1">
                                <span>{{ $message }}</span>
                            </p> 
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800">
                    </div>

                    <!-- Tahun Masuk -->
                    <div>
                        <label for="tahun_masuk" class="block text-xs font-semibold text-slate-700 mb-1">Tahun Masuk <span class="text-rose-500">*</span></label>
                        <input type="number" name="tahun_masuk" id="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}" required min="2000" max="2100"
                               class="w-full px-3 py-2 text-xs border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('tahun_masuk') border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-emerald-500 @enderror text-slate-800">
                        @error('tahun_masuk') 
                            <p class="text-rose-500 text-[11px] mt-1 flex items-center space-x-1">
                                <span>{{ $message }}</span>
                            </p> 
                        @enderror
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mt-4">
                    <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Tempat Tinggal</label>
                    <textarea name="alamat" id="alamat" rows="3" placeholder="Alamat lengkap orang tua/wali..."
                              class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Section 2: Data Akademik & Status -->
            <div>
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Penempatan Akademik</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tingkat -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tingkat <span class="text-rose-500">*</span></label>
                        @if(auth()->user()->role === 'bendahara')
                            <input type="text" value="{{ auth()->user()->tingkat->nama ?? 'Tidak Ada Tingkat' }}" 
                                   class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg bg-slate-50 text-slate-500 font-medium cursor-not-allowed" disabled>
                            <input type="hidden" name="tingkat_id" value="{{ auth()->user()->tingkat_id }}">
                        @else
                            <select name="tingkat_id" id="tingkat_id" required
                                    class="w-full px-3 py-2 text-xs border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('tingkat_id') border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-emerald-500 @enderror text-slate-800">
                                <option value="">Pilih Tingkat</option>
                                @foreach($tingkatList as $tingkat)
                                    <option value="{{ $tingkat->id }}" {{ old('tingkat_id') == $tingkat->id ? 'selected' : '' }}>
                                        {{ $tingkat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        @error('tingkat_id') 
                            <p class="text-rose-500 text-[11px] mt-1 flex items-center space-x-1">
                                <span>{{ $message }}</span>
                            </p> 
                        @enderror
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label for="kelas_id" class="block text-xs font-semibold text-slate-700 mb-1">Kelas</label>
                        <select name="kelas_id" id="kelas_id"
                                class="w-full px-3 py-2 text-xs border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('kelas_id') border-rose-500 focus:ring-rose-500 @else border-slate-200 focus:border-emerald-500 @enderror text-slate-800">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id') 
                            <p class="text-rose-500 text-[11px] mt-1 flex items-center space-x-1">
                                <span>{{ $message }}</span>
                            </p> 
                        @enderror
                        @if(auth()->user()->role === 'bendahara')
                            <p class="text-[11px] text-slate-400 mt-1">
                                Menampilkan kelas di unit {{ auth()->user()->tingkat->nama ?? '' }}.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Status -->
                <div class="mt-4">
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">Status Keaktifan <span class="text-rose-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full md:w-1/2 px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800">
                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ old('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="keluar" {{ old('status') == 'keluar' ? 'selected' : '' }}>Keluar / Pindah</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- Form Action Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('santri.index') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-200 rounded-lg transition-colors">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Santri</span>
            </button>
        </div>

    </form>
</div>
@endsection
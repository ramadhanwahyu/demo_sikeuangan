@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Edit Transaksi</h1>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui data transaksi yang telah dicatat.</p>
        </div>
        <a href="{{ route('transaksi.index') }}" class="px-3.5 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors inline-flex items-center space-x-1.5 shadow-sm">
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <form id="form-transaksi" action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Baris 1: Tanggal & Jenis Transaksi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}" required
                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('tanggal') border-rose-500 focus:ring-rose-500 @enderror">
                    @error('tanggal') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jenis Transaksi <span class="text-rose-500">*</span>
                    </label>
                    <select name="jenis" id="jenis" required
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('jenis') border-rose-500 focus:ring-rose-500 @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="pemasukan" {{ old('jenis', $transaksi->jenis) == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ old('jenis', $transaksi->jenis) == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                    @error('jenis') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Baris 2: Kategori & Jumlah -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id" required
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('kategori_id') border-rose-500 focus:ring-rose-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        <optgroup label="Pemasukan" id="optgroup-pemasukan">
                            @foreach($kategoriPemasukan as $kategori)
                            <option value="{{ $kategori->id }}" data-jenis="pemasukan" data-uang-jajan="{{ $kategori->is_uang_jajan ? '1' : '0' }}"
                                {{ old('kategori_id', $transaksi->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Pengeluaran" id="optgroup-pengeluaran">
                            @foreach($kategoriPengeluaran as $kategori)
                            <option value="{{ $kategori->id }}" data-jenis="pengeluaran" data-uang-jajan="{{ $kategori->is_uang_jajan ? '1' : '0' }}"
                                {{ old('kategori_id', $transaksi->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('kategori_id') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jumlah (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-slate-400 font-medium">Rp</span>
                        <!-- Display Input untuk format ribuan bertitik -->
                        @php
                            $jumlahVal = old('jumlah', $transaksi->jumlah);
                        @endphp
                        <input type="text" id="jumlah_format" value="{{ $jumlahVal ? number_format($jumlahVal, 0, ',', '.') : '' }}" placeholder="0" required inputmode="numeric"
                               class="w-full pl-8 pr-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 font-semibold @error('jumlah') border-rose-500 focus:ring-rose-500 @enderror">
                        <!-- Hidden input tanpa titik untuk backend -->
                        <input type="hidden" name="jumlah" id="jumlah" value="{{ $jumlahVal }}">
                    </div>
                    @error('jumlah') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Baris 3: Santri & Tingkat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Santri <span class="text-slate-400 font-normal">(Opsional / Wajib untuk Uang Jajan)</span>
                    </label>
                    <select name="santri_id" id="santri_id"
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('santri_id') border-rose-500 focus:ring-rose-500 @enderror">
                        <option value="">-- Pilih Santri --</option>
                        @foreach($santriList as $santri)
                        <option value="{{ $santri->id }}" data-tingkat="{{ $santri->tingkat_id }}" 
                            {{ old('santri_id', $transaksi->santri_id) == $santri->id ? 'selected' : '' }}>
                            {{ $santri->nama_lengkap }} ({{ $santri->tingkat->nama ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                    @error('santri_id') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tingkat <span class="text-rose-500">*</span>
                    </label>
                    <select name="tingkat_id" id="tingkat_id" required
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 disabled:bg-slate-50 disabled:text-slate-500 @error('tingkat_id') border-rose-500 focus:ring-rose-500 @enderror"
                            {{ auth()->user()->role === 'bendahara' ? 'disabled' : '' }}>
                        <option value="">-- Pilih Tingkat --</option>
                        @foreach($tingkatList as $tingkat)
                        <option value="{{ $tingkat->id }}" 
                            {{ old('tingkat_id', $transaksi->tingkat_id) == $tingkat->id || (auth()->user()->role === 'bendahara' && auth()->user()->tingkat_id == $tingkat->id) ? 'selected' : '' }}>
                            {{ $tingkat->nama }}
                        </option>
                        @endforeach
                    </select>
                    @if(auth()->user()->role === 'bendahara')
                        <input type="hidden" name="tingkat_id" value="{{ auth()->user()->tingkat_id }}">
                    @endif
                    @error('tingkat_id') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Baris 4: Keterangan -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Keterangan
                </label>
                <textarea name="keterangan" rows="3" placeholder="Tambahkan rincian atau catatan transaksi..."
                          class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 resize-none">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <a href="{{ route('transaksi.index') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm">
                    Update Transaksi
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    // Formatting Rupiah pada input jumlah
    const jumlahFormatInput = document.getElementById('jumlah_format');
    const jumlahRealInput = document.getElementById('jumlah');

    jumlahFormatInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
        
        // Simpan nilai murni ke hidden input
        jumlahRealInput.value = value;
        
        // Format tampilan dengan pemisah titik
        if (value) {
            this.value = parseInt(value, 10).toLocaleString('id-ID');
        } else {
            this.value = '';
        }
    });

    function filterKategori(jenis) {
        const select = document.getElementById('kategori_id');
        const optgroupPemasukan = document.getElementById('optgroup-pemasukan');
        const optgroupPengeluaran = document.getElementById('optgroup-pengeluaran');
        
        if (jenis === 'pemasukan') {
            optgroupPengeluaran.style.display = 'none';
            optgroupPemasukan.style.display = '';
            const selected = select.value;
            const selectedOpt = select.querySelector(`option[value="${selected}"]`);
            if (selectedOpt && selectedOpt.dataset.jenis !== 'pemasukan') {
                select.value = '';
            }
        } else if (jenis === 'pengeluaran') {
            optgroupPemasukan.style.display = 'none';
            optgroupPengeluaran.style.display = '';
            const selected = select.value;
            const selectedOpt = select.querySelector(`option[value="${selected}"]`);
            if (selectedOpt && selectedOpt.dataset.jenis !== 'pengeluaran') {
                select.value = '';
            }
        } else {
            optgroupPemasukan.style.display = '';
            optgroupPengeluaran.style.display = '';
        }
    }

    function checkUangJajanRequirement() {
        const select = document.getElementById('kategori_id');
        const selected = select.options[select.selectedIndex];
        const isUangJajan = selected ? selected.dataset.uangJajan === '1' : false;
        const santriSelect = document.getElementById('santri_id');
        if (isUangJajan) {
            santriSelect.setAttribute('required', 'required');
        } else {
            santriSelect.removeAttribute('required');
        }
    }

    document.getElementById('jenis').addEventListener('change', function() {
        filterKategori(this.value);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const jenis = document.getElementById('jenis').value;
        if (jenis) {
            filterKategori(jenis);
        }
        checkUangJajanRequirement();
    });

    document.getElementById('santri_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const tingkatId = selected.dataset.tingkat;
        const tingkatSelect = document.getElementById('tingkat_id');
        if (tingkatId && !tingkatSelect.disabled) {
            tingkatSelect.value = tingkatId;
        }
    });

    document.getElementById('kategori_id').addEventListener('change', checkUangJajanRequirement);
</script>
@endsection
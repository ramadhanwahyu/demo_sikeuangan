@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Tambah Transaksi</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                @if(($tipe ?? 'umum') === 'uang_jajan')
                    Form khusus pencatatan transaksi uang jajan santri.
                @else
                    Form khusus pencatatan transaksi kas umum.
                @endif
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ ($tipe ?? 'umum') === 'uang_jajan' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                {{ ($tipe ?? 'umum') === 'uang_jajan' ? 'Uang Jajan' : 'Kas Umum' }}
            </span>
            <a href="{{ route('transaksi.index', ['tipe' => $tipe ?? 'umum']) }}" class="px-3.5 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors inline-flex items-center space-x-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <form id="form-transaksi" action="{{ route('transaksi.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="tipe" id="tipe_transaksi" value="{{ $tipe ?? 'umum' }}">

            <!-- Baris 1: Tanggal & Jenis Transaksi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="tanggal" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('tanggal') border-rose-500 focus:ring-rose-500 @enderror">
                    @error('tanggal') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="jenis" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jenis Transaksi <span class="text-rose-500">*</span>
                    </label>
                    <select name="jenis" id="jenis" required
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('jenis') border-rose-500 focus:ring-rose-500 @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="pemasukan" {{ old('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan (+)</option>
                        <option value="pengeluaran" {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran (-)</option>
                    </select>
                    @error('jenis') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Baris 2: Kategori & Jumlah -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="kategori_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id" required
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('kategori_id') border-rose-500 focus:ring-rose-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        <optgroup label="Pemasukan" id="optgroup-pemasukan">
                            @foreach($kategoriPemasukan as $kategori)
                            <option value="{{ $kategori->id }}" 
                                    data-jenis="pemasukan" 
                                    data-uang-jajan="{{ $kategori->is_uang_jajan ? '1' : '0' }}"
                                    {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Pengeluaran" id="optgroup-pengeluaran">
                            @foreach($kategoriPengeluaran as $kategori)
                            <option value="{{ $kategori->id }}" 
                                    data-jenis="pengeluaran" 
                                    data-uang-jajan="{{ $kategori->is_uang_jajan ? '1' : '0' }}"
                                    {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
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
                    <label for="jumlah_display" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jumlah (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-slate-400 font-medium">Rp</span>
                        @php
                            $jumlahVal = old('jumlah');
                        @endphp
                        <input type="text" id="jumlah_display" value="{{ $jumlahVal ? number_format($jumlahVal, 0, ',', '.') : '' }}" placeholder="0" required inputmode="numeric"
                               class="w-full pl-8 pr-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 font-semibold @error('jumlah') border-rose-500 focus:ring-rose-500 @enderror">
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
                    <label for="santri_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Santri <span class="text-slate-400 font-normal">({{ ($tipe ?? 'umum') === 'uang_jajan' ? 'Wajib untuk Uang Jajan' : 'Opsional' }})</span>
                    </label>
                    <select name="santri_id" id="santri_id" {{ ($tipe ?? 'umum') === 'uang_jajan' ? 'required' : '' }}
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('santri_id') border-rose-500 focus:ring-rose-500 @enderror">
                        <option value="">-- Pilih Santri --</option>
                        @foreach($santriList as $santri)
                        <option value="{{ $santri->id }}" data-tingkat="{{ $santri->tingkat_id }}" 
                            {{ old('santri_id') == $santri->id ? 'selected' : '' }}>
                            {{ $santri->nama_lengkap }} ({{ $santri->tingkat->nama ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                    @error('santri_id') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label for="tingkat_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tingkat <span class="text-rose-500">*</span>
                    </label>
                    @if(auth()->user()->role === 'bendahara')
                        <input type="text" value="{{ auth()->user()->tingkat->nama ?? '-' }}" 
                               class="w-full px-3 py-2 text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-lg cursor-not-allowed" disabled>
                        <input type="hidden" name="tingkat_id" value="{{ auth()->user()->tingkat_id }}">
                    @else
                        <select name="tingkat_id" id="tingkat_id" required
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 @error('tingkat_id') border-rose-500 focus:ring-rose-500 @enderror">
                            <option value="">-- Pilih Tingkat --</option>
                            @foreach($tingkatList as $tingkat)
                            <option value="{{ $tingkat->id }}" {{ old('tingkat_id') == $tingkat->id ? 'selected' : '' }}>
                                {{ $tingkat->nama }}
                            </option>
                            @endforeach
                        </select>
                    @endif
                    @error('tingkat_id') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Baris 4: Keterangan -->
            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Keterangan
                </label>
                <textarea name="keterangan" id="keterangan" rows="3" placeholder="Tambahkan rincian atau catatan transaksi..."
                          class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-800 resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <a href="{{ route('transaksi.index', ['tipe' => $tipe ?? 'umum']) }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:text-slate-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm inline-flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Transaksi</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    // Format Angka Ribuan (Rupiah Display)
    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa  = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    }

    // Filter Kategori Berdasarkan Jenis & Tipe Transaksi (Uang Jajan / Umum)
    function filterKategori() {
        const jenis = document.getElementById('jenis').value;
        const tipe = document.getElementById('tipe_transaksi').value;
        const select = document.getElementById('kategori_id');
        const isUangJajanParam = (tipe === 'uang_jajan') ? '1' : '0';

        const optgroups = document.querySelectorAll('#kategori_id optgroup');
        optgroups.forEach(group => {
            let hasVisibleOptions = false;
            const options = group.querySelectorAll('option');

            options.forEach(option => {
                const matchJenis = !jenis || option.dataset.jenis === jenis;
                const matchTipe = option.dataset.uangJajan === isUangJajanParam;

                if (matchJenis && matchTipe) {
                    option.style.display = '';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            });

            group.style.display = hasVisibleOptions ? '' : 'none';
        });

        const selectedOpt = select.options[select.selectedIndex];
        if (selectedOpt && selectedOpt.style.display === 'none') {
            select.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const jumlahDisplay = document.getElementById('jumlah_display');
        const jumlahHidden = document.getElementById('jumlah');
        const form = document.getElementById('form-transaksi');

        // Jalankan filter awal sesuai tipe halaman & reload old value
        filterKategori();

        // Sync & mask rupiah input
        jumlahDisplay.addEventListener('input', function(e) {
            let rawValue = this.value.replace(/\D/g, '');
            jumlahHidden.value = rawValue;
            this.value = rawValue ? parseInt(rawValue, 10).toLocaleString('id-ID') : '';
        });

        // Event listener filter jenis
        document.getElementById('jenis')?.addEventListener('change', filterKategori);

        // Auto selection tingkat saat memilih santri
        document.getElementById('santri_id')?.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const tingkatId = selected.dataset.tingkat;
            const tingkatSelect = document.getElementById('tingkat_id');
            if (tingkatId && tingkatSelect && !tingkatSelect.disabled) {
                tingkatSelect.value = tingkatId;
            }
        });

        // Pastikan input hidden jumlah terisi angka murni saat submit
        form.addEventListener('submit', function() {
            jumlahHidden.value = jumlahDisplay.value.replace(/\D/g, '');
        });
    });
</script>
@endsection
@extends('layouts.app')

@section('title', $mode === 'tambah' ? 'Setor Uang Jajan Santri' : 'Tarik Uang Jajan Santri')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

    <!-- Clean Page Header -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ url()->previous() }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors" title="Kembali">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="text-lg font-bold text-slate-900">
                        {{ $mode === 'tambah' ? 'Setor Uang Jajan' : 'Tarik Uang Jajan' }}
                    </h1>
                    <span class="px-2.5 py-0.5 rounded text-[11px] font-semibold border {{ $mode === 'tambah' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                        {{ $mode === 'tambah' ? 'Pemasukan Tabungan' : 'Penarikan Kas' }}
                    </span>
                </div>
                <p class="text-slate-500 text-xs mt-0.5">Kelola sirkulasi tabungan dan uang saku harian santri.</p>
            </div>
        </div>
    </div>

    <!-- Card Profil Santri / Warning Info -->
    @if($santri)
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
            <!-- Accent Bar -->
            <div class="absolute top-0 left-0 right-0 h-1 {{ $mode === 'tambah' ? 'bg-emerald-500' : 'bg-blue-500' }}"></div>
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="p-3 {{ $mode === 'tambah' ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-600' }} rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Informasi Santri</span>
                        <h3 class="text-base font-bold text-slate-800">{{ $santri->nama_lengkap }}</h3>
                        <p class="text-xs text-slate-500">NIS: <span class="font-mono text-slate-700">{{ $santri->nis }}</span></p>
                    </div>
                </div>

                <div class="text-left sm:text-right pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Saldo Uang Jajan</span>
                    <div class="text-xl font-bold text-blue-600 tracking-tight whitespace-nowrap">
                        Rp {{ number_format($santri->saldo_uang_jajan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center space-x-3 text-blue-800">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-xs font-medium">Silakan pilih santri penerima/penarik terlebih dahulu untuk melanjutkan proses transaksi.</p>
        </div>
    @endif

    <!-- Alert Saldo Kosong jika mode Penarikan -->
    @if($mode === 'tarik' && $santri && $santri->saldo_uang_jajan <= 0)
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-center space-x-3 text-rose-800">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="text-xs font-bold">Tidak Dapat Melakukan Penarikan</h4>
                <p class="text-xs text-rose-700 mt-0.5">Saldo uang jajan santri saat ini bernilai Rp 0.</p>
            </div>
        </div>
    @else

    <!-- Main Form Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form id="form-uang-jajan" method="POST" 
              action="{{ $santri ? ($mode === 'tambah' ? route('uang-jajan.tambah.store', $santri->id) : route('uang-jajan.tarik.store', $santri->id)) : route('uang-jajan.saldo') }}" 
              class="space-y-4">
            @csrf

            <!-- Select Santri (Jika Belum Dipilih) -->
            @if(!$santri)
                <div>
                    <label for="santri_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Pilih Santri <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="santri_id" id="santri_id" required
                            class="w-full pl-3 pr-8 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 focus:ring-2 {{ $mode === 'tambah' ? 'focus:ring-emerald-500/20' : 'focus:ring-blue-500/20' }} transition @error('santri_id') border-rose-300 bg-rose-50/20 @enderror">
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santriList as $s)
                                <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_lengkap }} (NIS: {{ $s->nis }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('santri_id') 
                        <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            @endif

            <!-- Input Tanggal -->
            <div>
                <label for="tanggal" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Tanggal Transaksi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                        class="w-full px-3 py-2.5 text-xs text-slate-800 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 focus:ring-2 {{ $mode === 'tambah' ? 'focus:ring-emerald-500/20' : 'focus:ring-blue-500/20' }} transition @error('tanggal') border-rose-300 bg-rose-50/20 @enderror">
                </div>
                @error('tanggal') 
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Jumlah Nominal (Dengan Formatting Titik Auto) -->
            <div>
                <label for="jumlah_display" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nominal Transaksi (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative rounded-lg shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-slate-400 text-xs font-bold">Rp</span>
                    </div>
                    <!-- Virtual Input Display (dengan format titik) -->
                    <input type="text" id="jumlah_display" inputmode="numeric" required placeholder="0" value="{{ old('jumlah') ? number_format(old('jumlah'), 0, ',', '.') : '' }}"
                        class="w-full pl-9 pr-3 py-2.5 text-sm font-bold text-slate-900 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 focus:ring-2 {{ $mode === 'tambah' ? 'focus:ring-emerald-500/20' : 'focus:ring-blue-500/20' }} transition @error('jumlah') border-rose-300 bg-rose-50/20 @enderror">
                    
                    <!-- Hidden Real Input (Angka murni yang dikirim ke controller) -->
                    <input type="hidden" name="jumlah" id="jumlah" value="{{ old('jumlah') }}">
                </div>
                
                <!-- Quick Amount Buttons -->
                <div class="flex flex-wrap gap-1.5 mt-2">
                    <button type="button" onclick="setNominal(10000)" class="px-2.5 py-1 text-[11px] font-medium bg-slate-100 hover:bg-slate-200 text-slate-600 rounded border border-slate-200 transition">+10.000</button>
                    <button type="button" onclick="setNominal(20000)" class="px-2.5 py-1 text-[11px] font-medium bg-slate-100 hover:bg-slate-200 text-slate-600 rounded border border-slate-200 transition">+20.000</button>
                    <button type="button" onclick="setNominal(50000)" class="px-2.5 py-1 text-[11px] font-medium bg-slate-100 hover:bg-slate-200 text-slate-600 rounded border border-slate-200 transition">+50.000</button>
                    <button type="button" onclick="setNominal(100000)" class="px-2.5 py-1 text-[11px] font-medium bg-slate-100 hover:bg-slate-200 text-slate-600 rounded border border-slate-200 transition">+100.000</button>
                </div>

                @error('jumlah') 
                    <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Input Keterangan -->
            <div>
                <label for="keterangan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Keterangan / Catatan
                </label>
                <textarea name="keterangan" id="keterangan" rows="3" placeholder="Contoh: Titipan dari wali santri / Jajan harian"
                    class="w-full px-3 py-2 text-xs text-slate-800 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 focus:ring-2 {{ $mode === 'tambah' ? 'focus:ring-emerald-500/20' : 'focus:ring-blue-500/20' }} transition">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <a href="{{ url()->previous() }}" 
                   class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center space-x-1.5 px-4 py-2 text-xs font-semibold text-white rounded-lg transition-colors shadow-sm {{ $mode === 'tambah' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-blue-600 hover:bg-blue-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ $mode === 'tambah' ? 'Simpan Setoran' : 'Konfirmasi Penarikan' }}</span>
                </button>
            </div>
        </form>
    </div>
    @endif

</div>

<!-- Auto Formatting & Form Handler Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const displayInput = document.getElementById('jumlah_display');
        const hiddenInput = document.getElementById('jumlah');
        const form = document.getElementById('form-uang-jajan');
        const select = document.getElementById('santri_id');
        const mode = '{{ $mode }}';

        // Format angka dengan pemisah titik
        function formatRupiah(value) {
            const rawValue = value.replace(/\D/g, ''); // Ambil hanya digit angka
            if (!rawValue) return '';
            return new Intl.NumberFormat('id-ID').format(rawValue);
        }

        // Listener saat mengetik nominal
        displayInput?.addEventListener('input', function(e) {
            const formatted = formatRupiah(this.value);
            this.value = formatted;
            hiddenInput.value = this.value.replace(/\D/g, ''); // Simpan nilai murni tanpa titik di hidden input
        });

        // Function untuk tombol preset nominal (+10rb, +20rb, dst)
        window.setNominal = function(val) {
            if (displayInput && hiddenInput) {
                hiddenInput.value = val;
                displayInput.value = formatRupiah(val.toString());
            }
        };

        // Dinamisasi Action Form jika memilih Santri lewat Select
        select?.addEventListener('change', function() {
            const santriId = this.value;
            if (santriId) {
                const baseUrl = mode === 'tambah' 
                    ? `{{ url('/transaksi/uang-jajan/santri') }}/${santriId}/tambah`
                    : `{{ url('/transaksi/uang-jajan/santri') }}/${santriId}/tarik`;
                form.action = baseUrl;
            } else {
                form.action = '{{ route('uang-jajan.saldo') }}';
            }
        });

        // Validation & Safety Sync sebelum submit
        form?.addEventListener('submit', function(e) {
            if (select && !select.value) {
                e.preventDefault();
                alert('Silakan pilih santri terlebih dahulu.');
                return;
            }
            // Pastikan nilai hiddenInput terisi nilai numerik murni sebelum submit
            if (displayInput) {
                hiddenInput.value = displayInput.value.replace(/\D/g, '');
            }
        });
    });
</script>
@endsection
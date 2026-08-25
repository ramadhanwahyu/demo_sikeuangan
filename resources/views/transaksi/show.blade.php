@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Detail Transaksi</h2>
                <div class="space-x-2">
                    <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
                    <a href="{{ route('transaksi.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <table class="min-w-full">
                <tr>
                    <td class="py-3 text-gray-600 font-medium">Tanggal</td>
                    <td class="py-3">: {{ $transaksi->tanggal->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="py-3 text-gray-600 font-medium">Jenis</td>
                    <td class="py-3">
                        : <span class="px-2 py-1 text-xs rounded-full {{ $transaksi->jenis === 'pemasukan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaksi->jenis }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="py-3 text-gray-600 font-medium">Kategori</td>
                    <td class="py-3">: {{ $transaksi->kategori->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-3 text-gray-600 font-medium">Jumlah</td>
                    <td class="py-3 font-bold {{ $transaksi->jenis === 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                        : {{ $transaksi->jenis === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
                @if($transaksi->santri)
                <tr>
                    <td class="py-3 text-gray-600 font-medium">Santri</td>
                    <td class="py-3">: {{ $transaksi->santri->nama_lengkap }}</td>
                </tr>
                @endif
                <tr>
                    <td class="py-3 text-gray-600 font-medium">Tingkat</td>
                    <td class="py-3">: {{ $transaksi->tingkat->nama ?? '-' }}</td>
                </tr>
                @if($transaksi->keterangan)
                <tr>
                    <td class="py-3 text-gray-600 font-medium">Keterangan</td>
                    <td class="py-3">: {{ $transaksi->keterangan }}</td>
                </tr>
                @endif
                <tr>
                    <td class="py-3 text-gray-600 font-medium">Dicatat Oleh</td>
                    <td class="py-3">: {{ $transaksi->user->nama ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
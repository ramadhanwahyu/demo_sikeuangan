<?php

namespace App\Http\Controllers;

use App\Models\KategoriTransaksi;
use App\Models\Santri;
use App\Models\Tingkat;
use App\Models\TransaksiKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TransaksiKasController extends Controller
{
    /**
     * Cek otorisasi tingkat untuk bendahara (transaksi)
     */
    private function authorizeTingkat(?TransaksiKas $transaksi = null): void
    {
        $user = Auth::user();
        if ($user->role === 'bendahara') {
            if ($transaksi && $transaksi->tingkat_id !== $user->tingkat_id) {
                abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
            }
        }
    }

    /**
     * Cek otorisasi tingkat untuk bendahara (santri)
     */
    private function authorizeSantri(Santri $santri): void
    {
        $user = Auth::user();
        if ($user->role === 'bendahara' && $santri->tingkat_id !== $user->tingkat_id) {
            abort(403, 'Anda tidak memiliki akses ke data santri ini.');
        }
    }

    /**
     * Tentukan tipe transaksi: umum atau uang_jajan
     */
    private function getTipe(Request $request): string
    {
        $tipe = $request->query('tipe', 'umum');
        return in_array($tipe, ['umum', 'uang_jajan']) ? $tipe : 'umum';
    }

    /**
     * Ambil kategori uang jajan berdasarkan jenis
     */
    private function getKategoriUangJajan(string $jenis)
    {
        return KategoriTransaksi::where('is_uang_jajan', true)
            ->where('jenis', $jenis)
            ->first();
    }

    // =================================================================
    // CRUD TRANSAKSI (SUDAH ADA)
    // =================================================================

    public function index(Request $request)
    {
        $user = Auth::user();
        $tipe = $this->getTipe($request);

        $query = TransaksiKas::with(['kategori', 'santri', 'tingkat', 'user']);

        // Filter role bendahara
        if ($user->role === 'bendahara') {
            $query->where('tingkat_id', $user->tingkat_id);
        }

        // Filter tipe kas
        if ($tipe === 'uang_jajan') {
            $query->whereHas('kategori', function ($q) {
                $q->where('is_uang_jajan', true);
            });
        } else {
            $query->whereHas('kategori', function ($q) {
                $q->where('is_uang_jajan', false);
            });
        }

        // Filter tanggal
        if ($request->has('dari') && $request->dari) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->has('sampai') && $request->sampai) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        // Filter jenis
        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        // Filter kategori
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter tingkat untuk admin/pimpinan
        if ($request->has('tingkat_id') && $request->tingkat_id) {
            $query->where('tingkat_id', $request->tingkat_id);
        }

        $transaksi = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);

        // Hitung total menggunakan query terpisah agar clone tidak bermasalah
        $totalQuery = TransaksiKas::query();
        if ($user->role === 'bendahara') {
            $totalQuery->where('tingkat_id', $user->tingkat_id);
        }
        if ($tipe === 'uang_jajan') {
            $totalQuery->whereHas('kategori', fn($q) => $q->where('is_uang_jajan', true));
        } else {
            $totalQuery->whereHas('kategori', fn($q) => $q->where('is_uang_jajan', false));
        }
        if ($request->has('dari') && $request->dari) {
            $totalQuery->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->has('sampai') && $request->sampai) {
            $totalQuery->whereDate('tanggal', '<=', $request->sampai);
        }
        if ($request->has('jenis') && $request->jenis) {
            $totalQuery->where('jenis', $request->jenis);
        }
        if ($request->has('kategori_id') && $request->kategori_id) {
            $totalQuery->where('kategori_id', $request->kategori_id);
        }
        if ($request->has('tingkat_id') && $request->tingkat_id) {
            $totalQuery->where('tingkat_id', $request->tingkat_id);
        }

        $totalPemasukan = (clone $totalQuery)->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = (clone $totalQuery)->where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $kategoriList = KategoriTransaksi::orderBy('jenis')->orderBy('nama')->get();
        $tingkatList = Tingkat::all();

        return view('transaksi.index', compact(
            'transaksi',
            'kategoriList',
            'tingkatList',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'tipe'
        ));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $tipe = $this->getTipe($request); // "umum" atau "uang_jajan"

        // Filter kategori sesuai tipe
        $kategoriPemasukan = KategoriTransaksi::where('jenis', 'pemasukan')
            ->when($tipe === 'uang_jajan', fn($q) => $q->where('is_uang_jajan', true))
            ->when($tipe === 'umum', fn($q) => $q->where('is_uang_jajan', false))
            ->orderBy('nama')
            ->get();

        $kategoriPengeluaran = KategoriTransaksi::where('jenis', 'pengeluaran')
            ->when($tipe === 'uang_jajan', fn($q) => $q->where('is_uang_jajan', true))
            ->when($tipe === 'umum', fn($q) => $q->where('is_uang_jajan', false))
            ->orderBy('nama')
            ->get();

        $tingkatList = Tingkat::all();
        $santriList = Santri::where('status', 'aktif')
                            ->when($user->role === 'bendahara', function ($q) use ($user) {
                                $q->where('tingkat_id', $user->tingkat_id);
                            })
                            ->orderBy('nama_lengkap')
                            ->get();

        return view('transaksi.create', compact(
            'kategoriPemasukan',
            'kategoriPengeluaran',
            'tingkatList',
            'santriList',
            'tipe'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori_id' => 'required|exists:kategori_transaksi,id',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'santri_id' => 'nullable|exists:santri,id',
            'tingkat_id' => 'required|exists:tingkat,id',
            'tipe' => 'required|in:umum,uang_jajan', // validasi tipe
        ]);

        // Paksa tingkat_id untuk bendahara
        if ($user->role === 'bendahara') {
            $validated['tingkat_id'] = $user->tingkat_id;
        }

        $kategori = KategoriTransaksi::findOrFail($validated['kategori_id']);

        // Validasi kecocokan tipe dengan kategori
        if ($validated['tipe'] === 'umum' && $kategori->is_uang_jajan) {
            return back()->with('error', 'Kategori uang jajan tidak dapat digunakan untuk kas umum.')->withInput();
        }

        if ($validated['tipe'] === 'uang_jajan' && !$kategori->is_uang_jajan) {
            return back()->with('error', 'Kategori umum tidak dapat digunakan untuk uang jajan santri.')->withInput();
        }

        // Validasi uang jajan: santri wajib diisi
        if ($kategori->is_uang_jajan && empty($validated['santri_id'])) {
            return back()->with('error', 'Untuk transaksi uang jajan, santri wajib dipilih.')->withInput();
        }

        // Jika bukan uang jajan, set santri_id null
        if (!$kategori->is_uang_jajan) {
            $validated['santri_id'] = null;
        }

        // Jika ada santri_id, samakan tingkat dan update saldo
        if ($validated['santri_id']) {
            $santri = Santri::findOrFail($validated['santri_id']);
            $validated['tingkat_id'] = $santri->tingkat_id;

            if ($kategori->is_uang_jajan) {
                if ($validated['jenis'] === 'pemasukan') {
                    $santri->saldo_uang_jajan += $validated['jumlah'];
                } else {
                    if ($santri->saldo_uang_jajan < $validated['jumlah']) {
                        return back()->with('error', 'Saldo uang jajan santri tidak mencukupi.')->withInput();
                    }
                    $santri->saldo_uang_jajan -= $validated['jumlah'];
                }
                $santri->save();
            }
        }

        $validated['user_id'] = $user->id;
        TransaksiKas::create($validated);

        return redirect()->route('transaksi.index', ['tipe' => $validated['tipe']])
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show(TransaksiKas $transaksi)
    {
        $this->authorizeTingkat($transaksi);

        $transaksi->load(['kategori', 'santri', 'tingkat', 'user']);
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(TransaksiKas $transaksi)
    {
        $this->authorizeTingkat($transaksi);

        $user = Auth::user();
        $kategoriPemasukan = KategoriTransaksi::where('jenis', 'pemasukan')->orderBy('nama')->get();
        $kategoriPengeluaran = KategoriTransaksi::where('jenis', 'pengeluaran')->orderBy('nama')->get();
        $tingkatList = Tingkat::all();
        $santriList = Santri::where('status', 'aktif')
                            ->when($user->role === 'bendahara', function ($q) use ($user) {
                                $q->where('tingkat_id', $user->tingkat_id);
                            })
                            ->orderBy('nama_lengkap')
                            ->get();

        return view('transaksi.edit', compact(
            'transaksi',
            'kategoriPemasukan',
            'kategoriPengeluaran',
            'tingkatList',
            'santriList'
        ));
    }

    public function update(Request $request, TransaksiKas $transaksi)
    {
        $this->authorizeTingkat($transaksi);

        $user = Auth::user();

        // Rollback saldo santri dari transaksi lama
        if ($transaksi->santri_id) {
            $santriLama = Santri::find($transaksi->santri_id);
            $kategoriLama = KategoriTransaksi::find($transaksi->kategori_id);
            if ($kategoriLama->is_uang_jajan) {
                if ($transaksi->jenis === 'pemasukan') {
                    $santriLama->saldo_uang_jajan -= $transaksi->jumlah;
                } else {
                    $santriLama->saldo_uang_jajan += $transaksi->jumlah;
                }
                $santriLama->save();
            }
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori_id' => 'required|exists:kategori_transaksi,id',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'santri_id' => 'nullable|exists:santri,id',
            'tingkat_id' => 'required|exists:tingkat,id',
        ]);

        if ($user->role === 'bendahara') {
            $validated['tingkat_id'] = $user->tingkat_id;
        }

        $kategori = KategoriTransaksi::findOrFail($validated['kategori_id']);

        if ($kategori->is_uang_jajan && empty($validated['santri_id'])) {
            return back()->with('error', 'Untuk transaksi uang jajan, santri wajib dipilih.')->withInput();
        }

        if (!$kategori->is_uang_jajan) {
            $validated['santri_id'] = null;
        }

        // Update saldo dengan transaksi baru
        if ($validated['santri_id']) {
            $santriBaru = Santri::findOrFail($validated['santri_id']);
            $validated['tingkat_id'] = $santriBaru->tingkat_id;

            if ($kategori->is_uang_jajan) {
                if ($validated['jenis'] === 'pemasukan') {
                    $santriBaru->saldo_uang_jajan += $validated['jumlah'];
                } else {
                    if ($santriBaru->saldo_uang_jajan < $validated['jumlah']) {
                        return back()->with('error', 'Saldo uang jajan santri tidak mencukupi.')->withInput();
                    }
                    $santriBaru->saldo_uang_jajan -= $validated['jumlah'];
                }
                $santriBaru->save();
            }
        }

        $transaksi->update($validated);

        return redirect()->route('transaksi.index', ['tipe' => $kategori->is_uang_jajan ? 'uang_jajan' : 'umum'])
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(TransaksiKas $transaksi)
    {
        $this->authorizeTingkat($transaksi);

        // Rollback saldo santri jika transaksi uang jajan
        if ($transaksi->santri_id) {
            $santri = Santri::find($transaksi->santri_id);
            $kategori = KategoriTransaksi::find($transaksi->kategori_id);
            if ($kategori->is_uang_jajan) {
                if ($transaksi->jenis === 'pemasukan') {
                    $santri->saldo_uang_jajan -= $transaksi->jumlah;
                } else {
                    $santri->saldo_uang_jajan += $transaksi->jumlah;
                }
                $santri->save();
            }
        }

        $tipe = $transaksi->kategori->is_uang_jajan ? 'uang_jajan' : 'umum';
        $transaksi->delete();

        return redirect()->route('transaksi.index', ['tipe' => $tipe])
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    // =================================================================
    // FITUR SALDO UANG JAJAN SANTRI
    // =================================================================

    /**
     * Daftar saldo uang jajan semua santri
     */
    public function saldoSantri(Request $request)
    {
        $user = Auth::user();
        $query = Santri::with(['tingkat', 'kelas']);

        // Bendahara hanya melihat tingkatnya
        if ($user->role === 'bendahara') {
            $query->where('tingkat_id', $user->tingkat_id);
        }

        // Pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        // Filter tingkat (admin/pimpinan)
        if ($request->has('tingkat_id') && $request->tingkat_id) {
            $query->where('tingkat_id', $request->tingkat_id);
        }

        $santriList = $query->orderBy('nama_lengkap')->paginate(15);
        $tingkatList = Tingkat::all();

        return view('transaksi.saldo_santri', compact('santriList', 'tingkatList'));
    }

    /**
     * Detail transaksi uang jajan seorang santri
     */
    public function detailSantri(Request $request, Santri $santri)
    {
        $this->authorizeSantri($santri);

        $transaksi = TransaksiKas::with(['kategori', 'user'])
            ->where('santri_id', $santri->id)
            ->whereHas('kategori', fn($q) => $q->where('is_uang_jajan', true))
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        $totalMasuk = TransaksiKas::where('santri_id', $santri->id)
            ->where('jenis', 'pemasukan')
            ->whereHas('kategori', fn($q) => $q->where('is_uang_jajan', true))
            ->sum('jumlah');

        $totalKeluar = TransaksiKas::where('santri_id', $santri->id)
            ->where('jenis', 'pengeluaran')
            ->whereHas('kategori', fn($q) => $q->where('is_uang_jajan', true))
            ->sum('jumlah');

        $saldo = $totalMasuk - $totalKeluar;

        return view('transaksi.detail_santri', compact(
            'santri',
            'transaksi',
            'totalMasuk',
            'totalKeluar',
            'saldo'
        ));
    }

    /**
     * Form tambah saldo uang jajan santri
     */
    public function formTambahSaldo(Santri $santri)
    {
        $this->authorizeSantri($santri);

        return view('transaksi.form_uang_jajan', [
            'santri' => $santri,
            'mode' => 'tambah',
            'kategori' => $this->getKategoriUangJajan('pemasukan'),
        ]);
    }

    /**
     * Proses tambah saldo uang jajan santri
     */
    public function storeTambahSaldo(Request $request, Santri $santri)
    {
        $this->authorizeSantri($santri);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = $this->getKategoriUangJajan('pemasukan');

        if (!$kategori) {
            return back()->with('error', 'Kategori uang jajan santri (pemasukan) tidak ditemukan.');
        }

        // Update saldo santri
        $santri->saldo_uang_jajan += $validated['jumlah'];
        $santri->save();

        // Simpan transaksi
        TransaksiKas::create([
            'tanggal' => $validated['tanggal'],
            'kategori_id' => $kategori->id,
            'jenis' => 'pemasukan',
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? 'Tambah saldo uang jajan',
            'santri_id' => $santri->id,
            'tingkat_id' => $santri->tingkat_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('uang-jajan.saldo')
            ->with('success', 'Saldo uang jajan santri berhasil ditambahkan.');
    }

    /**
     * Form tarik uang jajan santri
     */
    public function formTarikUang(Santri $santri)
    {
        $this->authorizeSantri($santri);

        return view('transaksi.form_uang_jajan', [
            'santri' => $santri,
            'mode' => 'tarik',
            'kategori' => $this->getKategoriUangJajan('pengeluaran'),
        ]);
    }

    /**
     * Proses tarik uang jajan santri
     */
    public function storeTarikUang(Request $request, Santri $santri)
    {
        $this->authorizeSantri($santri);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Validasi saldo cukup
        if ($santri->saldo_uang_jajan < $validated['jumlah']) {
            return back()->with('error', 'Saldo uang jajan santri tidak mencukupi.')->withInput();
        }

        $kategori = $this->getKategoriUangJajan('pengeluaran');

        if (!$kategori) {
            return back()->with('error', 'Kategori uang jajan santri (pengeluaran) tidak ditemukan.');
        }

        // Update saldo santri
        $santri->saldo_uang_jajan -= $validated['jumlah'];
        $santri->save();

        // Simpan transaksi
        TransaksiKas::create([
            'tanggal' => $validated['tanggal'],
            'kategori_id' => $kategori->id,
            'jenis' => 'pengeluaran',
            'jumlah' => $validated['jumlah'],
            'keterangan' => $validated['keterangan'] ?? 'Tarik uang jajan',
            'santri_id' => $santri->id,
            'tingkat_id' => $santri->tingkat_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('uang-jajan.saldo')
            ->with('success', 'Uang jajan santri berhasil ditarik.');
    }

     /**
     * Form uang jajan umum (dari halaman transaksi uang jajan, tanpa santri terpilih)
     */
    public function formUangJajan(Request $request)
    {
        $user = Auth::user();

        // Ambil daftar santri aktif sesuai tingkat user (jika bendahara)
        $santriList = Santri::where('status', 'aktif')
            ->when($user->role === 'bendahara', function ($q) use ($user) {
                $q->where('tingkat_id', $user->tingkat_id);
            })
            ->orderBy('nama_lengkap')
            ->get();

        return view('transaksi.form_uang_jajan', [
            'santri' => null,
            'santriList' => $santriList,
            'mode' => $request->query('mode', 'tambah'), // default tambah
            'kategori' => null, // akan dipilih otomatis di view
        ]);
    }

}
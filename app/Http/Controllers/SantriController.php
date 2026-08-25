<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Santri;
use App\Models\Tingkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SantriController extends Controller
{
    /**
     * Cek otorisasi tingkat untuk bendahara
     */
    private function authorizeTingkat(?Santri $santri = null): void
    {
        $user = Auth::user();
        if ($user->role === 'bendahara') {
            if ($santri && $santri->tingkat_id !== $user->tingkat_id) {
                abort(403, 'Anda tidak memiliki akses ke data santri ini.');
            }
        }
    }

    /**
     * Ambil daftar kelas berdasarkan role user
     */
    private function getKelasList(): \Illuminate\Database\Eloquent\Collection
    {
        $user = Auth::user();

        if ($user->role === 'bendahara') {
            // Bendahara hanya melihat kelas di tingkatnya
            return Kelas::where('tingkat_id', $user->tingkat_id)
                ->orderBy('nama')
                ->get();
        }

        // Admin/pimpinan melihat semua kelas
        return Kelas::orderBy('tingkat_id')->orderBy('nama')->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Santri::with(['tingkat', 'kelas']);

        // Filter berdasarkan role
        if ($user->role === 'bendahara') {
            $query->where('tingkat_id', $user->tingkat_id);
        }

        // Filter pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        // Filter tingkat
        if ($request->has('tingkat_id') && $request->tingkat_id) {
            $query->where('tingkat_id', $request->tingkat_id);
        }

        // Filter kelas
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $santri = $query->orderBy('nama_lengkap')->paginate(10);
        $tingkatList = Tingkat::all();
        $kelasList = $this->getKelasList();

        return view('santri.index', compact('santri', 'tingkatList', 'kelasList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $tingkatList = Tingkat::all();
        $kelasList = $this->getKelasList();

        return view('santri.create', compact('tingkatList', 'kelasList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nis' => 'required|string|max:30|unique:santri,nis',
            'nama_lengkap' => 'required|string|max:150',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'tingkat_id' => 'required|exists:tingkat,id',
            'kelas_id' => [
                'nullable',
                'exists:kelas,id',
                function ($attribute, $value, $fail) use ($request, $user) {
                    if (!$value) {
                        return;
                    }

                    $kelas = Kelas::find($value);
                    $tingkatId = $user->role === 'bendahara'
                        ? $user->tingkat_id
                        : $request->input('tingkat_id');

                    if ($kelas && $kelas->tingkat_id != $tingkatId) {
                        $fail('Kelas yang dipilih tidak sesuai dengan tingkat.');
                    }
                },
            ],
            'tahun_masuk' => 'required|digits:4|integer|min:2000|max:2100',
            'status' => 'required|in:aktif,lulus,keluar',
        ]);

        // Paksa tingkat_id untuk bendahara
        if ($user->role === 'bendahara') {
            $validated['tingkat_id'] = $user->tingkat_id;
        }

        $validated['saldo_uang_jajan'] = 0;

        Santri::create($validated);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Santri $santri)
    {
        $this->authorizeTingkat($santri);

        $santri->load(['tingkat', 'kelas', 'waliSantri.user', 'sppBills' => function ($q) {
            $q->orderBy('tahun_ajaran_id')->orderBy('bulan');
        }]);

        return view('santri.show', compact('santri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Santri $santri)
    {
        $this->authorizeTingkat($santri);

        $tingkatList = Tingkat::all();
        $kelasList = $this->getKelasList();

        return view('santri.edit', compact('santri', 'tingkatList', 'kelasList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Santri $santri)
    {
        $this->authorizeTingkat($santri);

        $user = Auth::user();

        $validated = $request->validate([
            'nis' => 'required|string|max:30|unique:santri,nis,' . $santri->id,
            'nama_lengkap' => 'required|string|max:150',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'tingkat_id' => 'required|exists:tingkat,id',
            'kelas_id' => [
                'nullable',
                'exists:kelas,id',
                function ($attribute, $value, $fail) use ($request, $user) {
                    if (!$value) {
                        return;
                    }

                    $kelas = Kelas::find($value);
                    $tingkatId = $user->role === 'bendahara'
                        ? $user->tingkat_id
                        : $request->input('tingkat_id');

                    if ($kelas && $kelas->tingkat_id != $tingkatId) {
                        $fail('Kelas yang dipilih tidak sesuai dengan tingkat.');
                    }
                },
            ],
            'tahun_masuk' => 'required|digits:4|integer|min:2000|max:2100',
            'status' => 'required|in:aktif,lulus,keluar',
        ]);

        // Paksa tingkat_id untuk bendahara
        if ($user->role === 'bendahara') {
            $validated['tingkat_id'] = $user->tingkat_id;
        }

        $santri->update($validated);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Santri $santri)
    {
        $this->authorizeTingkat($santri);

        // Cek apakah santri memiliki transaksi atau tagihan
        if ($santri->transaksiKas()->exists() || $santri->sppBills()->exists()) {
            return redirect()->route('santri.index')
                ->with('error', 'Santri tidak dapat dihapus karena memiliki data transaksi atau tagihan.');
        }

        $santri->delete();

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\TransaksiKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenggunaTransaksiKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiKeluar::with(['barang.satuan']);

        if ($request->cari) {
            $query->whereHas('barang', fn($q) =>
                $q->where('nama_barang', 'like', '%'.$request->cari.'%')
            );
        }
        if ($request->dari)   $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal', '<=', $request->sampai);

        $data = $query->latest('tanggal')->paginate(20);
        return view('pengguna.transaksi-keluar.index', compact('data'));
    }

    public function create()
    {
        // Hanya tampilkan barang yang masih ada stok
        $barangs     = Barang::with('satuan')
                             ->where('aktif', true)
                             ->where('stok', '>', 0)
                             ->orderBy('nama_barang')->get();
        $noTransaksi = $this->generateNo();
        return view('pengguna.transaksi-keluar.form', compact('barangs', 'noTransaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|string|max:30|unique:transaksi_keluar,no_transaksi',
            'barang_id'    => 'required|exists:barang,id',
            'tanggal'      => 'required|date',
            'periode'      => 'required|string|max:20',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ], [
            'barang_id.required' => 'Pilih barang terlebih dahulu.',
            'jumlah.min'         => 'Jumlah minimal 1.',
        ]);

        // Validasi stok mencukupi
        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->stok < $request->jumlah) {
            return back()->withInput()
                         ->with('error', "Stok tidak cukup! Stok tersedia: {$barang->stok} {$barang->satuan->nama}.");
        }

        DB::transaction(function () use ($request) {
            TransaksiKeluar::create([
                'no_transaksi' => $request->no_transaksi,
                'barang_id'    => $request->barang_id,
                'tanggal'      => $request->tanggal,
                'periode'      => $request->periode,
                'jumlah'       => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'penerima'     => $request->penerima,
                'no_dokumen'   => $request->no_dokumen,
                'keterangan'   => $request->keterangan ?? 'Input via mode Pengguna',
            ]);

            // Stok otomatis berkurang
            Barang::where('id', $request->barang_id)->decrement('stok', $request->jumlah);
        });

        return redirect()->route('pengguna.transaksi-keluar.index')
                         ->with('success', 'Barang keluar berhasil dicatat. Stok otomatis dikurangi.');
    }

    private function generateNo(): string
    {
        $prefix = 'TK-' . now()->format('Ymd') . '-';
        $last   = TransaksiKeluar::where('no_transaksi', 'like', $prefix.'%')
                                  ->orderByDesc('no_transaksi')->value('no_transaksi');
        $urut   = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }
}

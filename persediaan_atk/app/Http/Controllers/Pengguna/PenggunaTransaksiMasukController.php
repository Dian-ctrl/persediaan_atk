<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenggunaTransaksiMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiMasuk::with(['barang.satuan']);

        if ($request->cari) {
            $query->whereHas('barang', fn($q) =>
                $q->where('nama_barang', 'like', '%'.$request->cari.'%')
            );
        }
        if ($request->dari)   $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->sampai) $query->whereDate('tanggal', '<=', $request->sampai);

        $data = $query->latest('tanggal')->paginate(20);
        return view('pengguna.transaksi-masuk.index', compact('data'));
    }

    public function create()
    {
        $barangs     = Barang::with('satuan')->where('aktif', true)->orderBy('nama_barang')->get();
        $noTransaksi = $this->generateNo();
        return view('pengguna.transaksi-masuk.form', compact('barangs', 'noTransaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|string|max:30|unique:transaksi_masuk,no_transaksi',
            'barang_id'    => 'required|exists:barang,id',
            'tanggal'      => 'required|date',
            'periode'      => 'required|string|max:20',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ], [
            'barang_id.required' => 'Pilih barang terlebih dahulu.',
            'jumlah.min'         => 'Jumlah minimal 1.',
        ]);

        DB::transaction(function () use ($request) {
            TransaksiMasuk::create([
                'no_transaksi' => $request->no_transaksi,
                'barang_id'    => $request->barang_id,
                'tanggal'      => $request->tanggal,
                'periode'      => $request->periode,
                'jumlah'       => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'sumber'       => $request->sumber,
                'no_dokumen'   => $request->no_dokumen,
                'keterangan'   => $request->keterangan ?? 'Input via mode Pengguna',
            ]);

            // Stok otomatis bertambah
            Barang::where('id', $request->barang_id)->increment('stok', $request->jumlah);
        });

        return redirect()->route('pengguna.transaksi-masuk.index')
                         ->with('success', 'Barang masuk berhasil dicatat. Stok otomatis diperbarui.');
    }

    private function generateNo(): string
    {
        $prefix = 'TM-' . now()->format('Ymd') . '-';
        $last   = TransaksiMasuk::where('no_transaksi', 'like', $prefix.'%')
                                 ->orderByDesc('no_transaksi')->value('no_transaksi');
        $urut   = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }
}

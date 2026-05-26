<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransaksiMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiMasuk::with('barang.satuan');

        if ($request->q) {
            $query->whereHas('barang', fn($q) => $q->where('nama_barang', 'like', "%{$request->q}%"));
        }
        if ($request->dari) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->sampai) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }
        if ($request->periode) {
            $query->where('periode', 'like', "%{$request->periode}%");
        }

        $transaksis = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(20);
        return view('transaksi.masuk-index', compact('transaksis'));
    }

    public function create()
    {
        $barangs    = Barang::with('satuan')->orderBy('nama_barang')->get();
        $noTransaksi = $this->generateNoTransaksi();
        return view('transaksi.masuk-form', compact('barangs', 'noTransaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'periode'      => 'required|string|max:30',
            'barang_id'    => 'required|exists:barangs,id',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:0',
        ]);

        $total = $request->jumlah * $request->harga_satuan;

        TransaksiMasuk::create([
            'no_transaksi' => $this->generateNoTransaksi(),
            'tanggal'      => $request->tanggal,
            'periode'      => $request->periode,
            'barang_id'    => $request->barang_id,
            'jumlah'       => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total'        => $total,
            'no_dokumen'   => $request->no_dokumen,
            'keterangan'   => $request->keterangan,
        ]);

        // Update stok barang
        Barang::find($request->barang_id)->increment('stok', $request->jumlah);

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil disimpan.');
    }

    public function edit(TransaksiMasuk $barangMasuk)
    {
        $barangs    = Barang::with('satuan')->orderBy('nama_barang')->get();
        $transaksi  = $barangMasuk;
        $noTransaksi = $transaksi->no_transaksi;
        return view('transaksi.masuk-form', compact('transaksi', 'barangs', 'noTransaksi'));
    }

    public function update(Request $request, TransaksiMasuk $barangMasuk)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'periode'      => 'required|string|max:30',
            'barang_id'    => 'required|exists:barangs,id',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:0',
        ]);

        // Kembalikan stok lama, tambah stok baru
        Barang::find($barangMasuk->barang_id)->decrement('stok', $barangMasuk->jumlah);

        $barangMasuk->update([
            'tanggal'      => $request->tanggal,
            'periode'      => $request->periode,
            'barang_id'    => $request->barang_id,
            'jumlah'       => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total'        => $request->jumlah * $request->harga_satuan,
            'no_dokumen'   => $request->no_dokumen,
            'keterangan'   => $request->keterangan,
        ]);

        Barang::find($request->barang_id)->increment('stok', $request->jumlah);

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy(TransaksiMasuk $barangMasuk)
    {
        // Kembalikan stok
        Barang::find($barangMasuk->barang_id)->decrement('stok', $barangMasuk->jumlah);
        $barangMasuk->delete();
        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function generateNoTransaksi(): string
    {
        $prefix = 'TM-' . date('Ymd');
        $last = TransaksiMasuk::where('no_transaksi', 'like', $prefix . '%')->orderByDesc('id')->first();
        $seq  = $last ? ((int) substr($last->no_transaksi, -4)) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TransaksiKeluar;
use Illuminate\Http\Request;

class TransaksiKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiKeluar::with('barang.satuan');

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
        return view('transaksi.keluar-index', compact('transaksis'));
    }

    public function create()
    {
        $barangs     = Barang::with('satuan')->where('stok', '>', 0)->orderBy('nama_barang')->get();
        $noTransaksi = $this->generateNoTransaksi();
        return view('transaksi.keluar-form', compact('barangs', 'noTransaksi'));
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

        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->stok < $request->jumlah) {
            return back()->withErrors(['jumlah' => "Stok tidak mencukupi. Stok tersedia: {$barang->stok}"])->withInput();
        }

        TransaksiKeluar::create([
            'no_transaksi' => $this->generateNoTransaksi(),
            'tanggal'      => $request->tanggal,
            'periode'      => $request->periode,
            'barang_id'    => $request->barang_id,
            'jumlah'       => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total'        => $request->jumlah * $request->harga_satuan,
            'penerima'     => $request->penerima,
            'keterangan'   => $request->keterangan,
        ]);

        $barang->decrement('stok', $request->jumlah);

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil disimpan.');
    }

    public function edit(TransaksiKeluar $barangKeluar)
    {
        $barangs    = Barang::with('satuan')->orderBy('nama_barang')->get();
        $transaksi  = $barangKeluar;
        $noTransaksi = $transaksi->no_transaksi;
        return view('transaksi.keluar-form', compact('transaksi', 'barangs', 'noTransaksi'));
    }

    public function update(Request $request, TransaksiKeluar $barangKeluar)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'periode'      => 'required|string|max:30',
            'barang_id'    => 'required|exists:barangs,id',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:0',
        ]);

        // Kembalikan stok lama
        Barang::find($barangKeluar->barang_id)->increment('stok', $barangKeluar->jumlah);

        $barang = Barang::find($request->barang_id);
        if ($barang->stok < $request->jumlah) {
            Barang::find($barangKeluar->barang_id)->decrement('stok', $barangKeluar->jumlah);
            return back()->withErrors(['jumlah' => "Stok tidak mencukupi. Stok tersedia: {$barang->stok}"])->withInput();
        }

        $barangKeluar->update([
            'tanggal'      => $request->tanggal,
            'periode'      => $request->periode,
            'barang_id'    => $request->barang_id,
            'jumlah'       => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total'        => $request->jumlah * $request->harga_satuan,
            'penerima'     => $request->penerima,
            'keterangan'   => $request->keterangan,
        ]);

        $barang->decrement('stok', $request->jumlah);

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy(TransaksiKeluar $barangKeluar)
    {
        Barang::find($barangKeluar->barang_id)->increment('stok', $barangKeluar->jumlah);
        $barangKeluar->delete();
        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    private function generateNoTransaksi(): string
    {
        $prefix = 'TK-' . date('Ymd');
        $last   = TransaksiKeluar::where('no_transaksi', 'like', $prefix . '%')->orderByDesc('id')->first();
        $seq    = $last ? ((int) substr($last->no_transaksi, -4)) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}

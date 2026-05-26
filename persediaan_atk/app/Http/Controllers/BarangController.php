<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Satuan;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'satuan']);

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->q}%")
                  ->orWhere('kode', 'like', "%{$request->q}%");
            });
        }

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->stok === 'habis') {
            $query->where('stok', 0);
        } elseif ($request->stok === 'ada') {
            $query->where('stok', '>', 0);
        }

        $barangs   = $query->orderBy('nama_barang')->paginate(20);
        $kategoris = KategoriBarang::orderBy('nama_kategori')->get();

        return view('barang.index', compact('barangs', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriBarang::orderBy('nama_kategori')->get();
        $satuans   = Satuan::orderBy('nama_satuan')->get();
        return view('barang.form', compact('kategoris', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'         => 'required|max:20|unique:barangs,kode',
            'nama_barang'  => 'required|max:150',
            'kategori_id'  => 'required|exists:kategori_barangs,id',
            'satuan_id'    => 'required|exists:satuans,id',
            'harga_satuan' => 'required|integer|min:0',
            'stok'         => 'nullable|integer|min:0',
        ]);

        Barang::create([
            'kode'         => strtoupper($request->kode),
            'nama_barang'  => $request->nama_barang,
            'kategori_id'  => $request->kategori_id,
            'satuan_id'    => $request->satuan_id,
            'harga_satuan' => $request->harga_satuan,
            'stok'         => $request->stok ?? 0,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'satuan', 'transaksiMasuk', 'transaksiKeluar']);
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategoris = KategoriBarang::orderBy('nama_kategori')->get();
        $satuans   = Satuan::orderBy('nama_satuan')->get();
        return view('barang.form', compact('barang', 'kategoris', 'satuans'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode'         => 'required|max:20|unique:barangs,kode,' . $barang->id,
            'nama_barang'  => 'required|max:150',
            'kategori_id'  => 'required|exists:kategori_barangs,id',
            'satuan_id'    => 'required|exists:satuans,id',
            'harga_satuan' => 'required|integer|min:0',
            'stok'         => 'nullable|integer|min:0',
        ]);

        $barang->update([
            'kode'         => strtoupper($request->kode),
            'nama_barang'  => $request->nama_barang,
            'kategori_id'  => $request->kategori_id,
            'satuan_id'    => $request->satuan_id,
            'harga_satuan' => $request->harga_satuan,
            'stok'         => $request->stok ?? $barang->stok,
            'keterangan'   => $request->keterangan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->transaksiMasuk()->count() || $barang->transaksiKeluar()->count()) {
            return redirect()->route('barang.index')->with('error', 'Barang tidak bisa dihapus karena sudah memiliki transaksi.');
        }
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}

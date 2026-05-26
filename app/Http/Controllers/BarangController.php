<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Satuan;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'satuan']);

        if ($request->cari) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%'.$request->cari.'%')
                  ->orWhere('kode_barang', 'like', '%'.$request->cari.'%');
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

        $data     = $query->orderBy('kategori_id')->orderBy('nama_barang')->paginate(25);
        $kategoris = KategoriBarang::orderBy('kode')->get();

        return view('barang.index', compact('data', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriBarang::orderBy('kode')->get();
        $satuans   = Satuan::orderBy('nama')->get();
        return view('barang.form', compact('kategoris', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|string|max:20|unique:barang,kode_barang',
            'nama_barang'  => 'required|string|max:200',
            'kategori_id'  => 'required|exists:kategori_barang,id',
            'satuan_id'    => 'required|exists:satuan,id',
            'stok'         => 'required|integer|min:0',
            'harga_satuan' => 'required|numeric|min:0',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique'   => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'kategori_id.required' => 'Pilih kategori barang.',
            'satuan_id.required'   => 'Pilih satuan barang.',
        ]);

        Barang::create([
            'kode_barang'  => strtoupper($request->kode_barang),
            'nama_barang'  => $request->nama_barang,
            'kategori_id'  => $request->kategori_id,
            'satuan_id'    => $request->satuan_id,
            'stok'         => $request->stok,
            'harga_satuan' => $request->harga_satuan,
            'keterangan'   => $request->keterangan,
            'aktif'        => true,
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'satuan']);
        $riwayatMasuk  = TransaksiMasuk::where('barang_id', $barang->id)
                                        ->latest('tanggal')->limit(10)->get();
        $riwayatKeluar = TransaksiKeluar::where('barang_id', $barang->id)
                                         ->latest('tanggal')->limit(10)->get();
        return view('barang.show', compact('barang', 'riwayatMasuk', 'riwayatKeluar'));
    }

    public function edit(Barang $barang)
    {
        $kategoris = KategoriBarang::orderBy('kode')->get();
        $satuans   = Satuan::orderBy('nama')->get();
        return view('barang.form', compact('barang', 'kategoris', 'satuans'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang'  => 'required|string|max:20|unique:barang,kode_barang,'.$barang->id,
            'nama_barang'  => 'required|string|max:200',
            'kategori_id'  => 'required|exists:kategori_barang,id',
            'satuan_id'    => 'required|exists:satuan,id',
            'stok'         => 'required|integer|min:0',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        $barang->update([
            'kode_barang'  => strtoupper($request->kode_barang),
            'nama_barang'  => $request->nama_barang,
            'kategori_id'  => $request->kategori_id,
            'satuan_id'    => $request->satuan_id,
            'stok'         => $request->stok,
            'harga_satuan' => $request->harga_satuan,
            'keterangan'   => $request->keterangan,
            'aktif'        => $request->boolean('aktif'),
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->transaksiMasuk()->count() > 0 || $barang->transaksiKeluar()->count() > 0) {
            return redirect()->route('barang.index')
                             ->with('error', 'Barang tidak dapat dihapus karena sudah memiliki transaksi.');
        }

        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus.');
    }
}

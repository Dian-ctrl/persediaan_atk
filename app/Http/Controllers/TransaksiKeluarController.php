<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TransaksiKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiKeluar::with(['barang.satuan']);

        if ($request->cari) {
            $query->whereHas('barang', fn($q) =>
                $q->where('nama_barang', 'like', '%'.$request->cari.'%')
            );
        }
        if ($request->dari)    $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->sampai)  $query->whereDate('tanggal', '<=', $request->sampai);
        if ($request->periode) $query->where('periode', 'like', '%'.$request->periode.'%');

        $data = $query->latest('tanggal')->paginate(20);

        return view('transaksi-keluar.index', compact('data'));
    }

    public function create()
    {
        $barangs     = Barang::with('satuan')->where('aktif', true)->orderBy('nama_barang')->get();
        $noTransaksi = $this->generateNoTransaksi();
        return view('transaksi-keluar.form', compact('barangs', 'noTransaksi'));
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
        ]);

        // Cek stok cukup
        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->stok < $request->jumlah) {
            return back()->withInput()
                         ->with('error', "Stok tidak cukup. Stok tersedia: {$barang->stok} {$barang->satuan->nama}.");
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
                'keterangan'   => $request->keterangan,
            ]);

            Barang::where('id', $request->barang_id)
                  ->decrement('stok', $request->jumlah);
        });

        return redirect()->route('transaksi-keluar.index')
                         ->with('success', 'Transaksi keluar berhasil disimpan. Stok dikurangi.');
    }

    public function edit(TransaksiKeluar $transaksiKeluar)
    {
        $transaksi   = $transaksiKeluar;
        $barangs     = Barang::with('satuan')->where('aktif', true)->orderBy('nama_barang')->get();
        $noTransaksi = $transaksi->no_transaksi;
        return view('transaksi-keluar.form', compact('transaksi', 'barangs', 'noTransaksi'));
    }

    public function update(Request $request, TransaksiKeluar $transaksiKeluar)
    {
        $request->validate([
            'no_transaksi' => 'required|string|max:30|unique:transaksi_keluar,no_transaksi,'.$transaksiKeluar->id,
            'barang_id'    => 'required|exists:barang,id',
            'tanggal'      => 'required|date',
            'periode'      => 'required|string|max:20',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $transaksiKeluar) {
            $oldBarangId  = $transaksiKeluar->barang_id;
            $oldJumlah    = $transaksiKeluar->jumlah;

            // Kembalikan stok lama
            Barang::where('id', $oldBarangId)->increment('stok', $oldJumlah);

            // Validasi stok baru
            $barangBaru = Barang::find($request->barang_id);
            if ($barangBaru->stok < $request->jumlah) {
                throw new \Exception("Stok tidak cukup setelah rollback.");
            }

            $transaksiKeluar->update([
                'no_transaksi' => $request->no_transaksi,
                'barang_id'    => $request->barang_id,
                'tanggal'      => $request->tanggal,
                'periode'      => $request->periode,
                'jumlah'       => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'penerima'     => $request->penerima,
                'no_dokumen'   => $request->no_dokumen,
                'keterangan'   => $request->keterangan,
            ]);

            // Kurangi stok baru
            Barang::where('id', $request->barang_id)->decrement('stok', $request->jumlah);
        });

        return redirect()->route('transaksi-keluar.index')
                         ->with('success', 'Transaksi keluar berhasil diperbarui.');
    }

    public function destroy(TransaksiKeluar $transaksiKeluar)
    {
        DB::transaction(function () use ($transaksiKeluar) {
            Barang::where('id', $transaksiKeluar->barang_id)
                  ->increment('stok', $transaksiKeluar->jumlah);
            $transaksiKeluar->delete();
        });

        return redirect()->route('transaksi-keluar.index')
                         ->with('success', 'Transaksi dihapus dan stok dikembalikan.');
    }

    private function generateNoTransaksi(): string
    {
        $prefix = 'TK-' . now()->format('Ymd') . '-';
        $last   = TransaksiKeluar::where('no_transaksi', 'like', $prefix . '%')
                                  ->orderByDesc('no_transaksi')->value('no_transaksi');
        $urut   = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }
}

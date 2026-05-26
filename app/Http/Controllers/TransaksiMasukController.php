<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TransaksiMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiMasuk::with(['barang.satuan']);

        if ($request->cari) {
            $query->whereHas('barang', fn($q) =>
                $q->where('nama_barang', 'like', '%'.$request->cari.'%')
            );
        }
        if ($request->dari)    $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->sampai)  $query->whereDate('tanggal', '<=', $request->sampai);
        if ($request->periode) $query->where('periode', 'like', '%'.$request->periode.'%');

        $data = $query->latest('tanggal')->paginate(20);

        return view('transaksi-masuk.index', compact('data'));
    }

    public function create()
    {
        $barangs     = Barang::with('satuan')->where('aktif', true)->orderBy('nama_barang')->get();
        $noTransaksi = $this->generateNoTransaksi();
        return view('transaksi-masuk.form', compact('barangs', 'noTransaksi'));
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
            'no_transaksi.required' => 'Nomor transaksi wajib diisi.',
            'barang_id.required'    => 'Pilih barang.',
            'jumlah.required'       => 'Jumlah wajib diisi.',
            'jumlah.min'            => 'Jumlah minimal 1.',
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
                'keterangan'   => $request->keterangan,
            ]);

            // Update stok barang
            Barang::where('id', $request->barang_id)
                  ->increment('stok', $request->jumlah);
        });

        return redirect()->route('transaksi-masuk.index')
                         ->with('success', 'Transaksi masuk berhasil disimpan. Stok diperbarui.');
    }

    public function edit(TransaksiMasuk $transaksiMasuk)
    {
        $transaksi = $transaksiMasuk;
        $barangs   = Barang::with('satuan')->where('aktif', true)->orderBy('nama_barang')->get();
        $noTransaksi = $transaksi->no_transaksi;
        return view('transaksi-masuk.form', compact('transaksi', 'barangs', 'noTransaksi'));
    }

    public function update(Request $request, TransaksiMasuk $transaksiMasuk)
    {
        $request->validate([
            'no_transaksi' => 'required|string|max:30|unique:transaksi_masuk,no_transaksi,'.$transaksiMasuk->id,
            'barang_id'    => 'required|exists:barang,id',
            'tanggal'      => 'required|date',
            'periode'      => 'required|string|max:20',
            'jumlah'       => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $transaksiMasuk) {
            $selisihJumlah = $request->jumlah - $transaksiMasuk->jumlah;
            $oldBarangId   = $transaksiMasuk->barang_id;

            $transaksiMasuk->update([
                'no_transaksi' => $request->no_transaksi,
                'barang_id'    => $request->barang_id,
                'tanggal'      => $request->tanggal,
                'periode'      => $request->periode,
                'jumlah'       => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'sumber'       => $request->sumber,
                'no_dokumen'   => $request->no_dokumen,
                'keterangan'   => $request->keterangan,
            ]);

            // Adjust stok: rollback lama, apply baru
            if ($oldBarangId != $request->barang_id) {
                Barang::where('id', $oldBarangId)->decrement('stok', $transaksiMasuk->getOriginal('jumlah'));
                Barang::where('id', $request->barang_id)->increment('stok', $request->jumlah);
            } else {
                if ($selisihJumlah > 0) {
                    Barang::where('id', $request->barang_id)->increment('stok', $selisihJumlah);
                } elseif ($selisihJumlah < 0) {
                    Barang::where('id', $request->barang_id)->decrement('stok', abs($selisihJumlah));
                }
            }
        });

        return redirect()->route('transaksi-masuk.index')
                         ->with('success', 'Transaksi masuk berhasil diperbarui.');
    }

    public function destroy(TransaksiMasuk $transaksiMasuk)
    {
        DB::transaction(function () use ($transaksiMasuk) {
            // Kembalikan stok
            Barang::where('id', $transaksiMasuk->barang_id)
                  ->decrement('stok', $transaksiMasuk->jumlah);
            $transaksiMasuk->delete();
        });

        return redirect()->route('transaksi-masuk.index')
                         ->with('success', 'Transaksi dihapus dan stok dikembalikan.');
    }

    private function generateNoTransaksi(): string
    {
        $prefix = 'TM-' . now()->format('Ymd') . '-';
        $last   = TransaksiMasuk::where('no_transaksi', 'like', $prefix . '%')
                                 ->orderByDesc('no_transaksi')->value('no_transaksi');
        $urut   = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }
}

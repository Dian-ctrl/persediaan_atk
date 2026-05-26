<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = KategoriBarang::orderBy('nama_kategori')->get();

        if (!$request->action) {
            return view('laporan.index', compact('kategoris'));
        }

        // Build query
        $query = Barang::with(['kategori', 'satuan']);

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $barangsRaw = $query->orderBy('nama_barang')->get();

        // Hitung masuk & keluar per barang berdasarkan filter periode
        $barangs = $barangsRaw->map(function ($b) use ($request) {
            $masukQ = TransaksiMasuk::where('barang_id', $b->id);
            $keluarQ = TransaksiKeluar::where('barang_id', $b->id);

            if (!$request->semua_bulan) {
                $bulan = $request->bulan ?? date('n');
                $tahun = $request->tahun ?? date('Y');
                $masukQ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                $keluarQ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            }

            $totalMasuk  = $masukQ->sum('jumlah');
            $totalKeluar = $keluarQ->sum('jumlah');
            $b->total_masuk  = $totalMasuk;
            $b->total_keluar = $totalKeluar;
            $b->stok_awal    = $b->stok - $totalMasuk + $totalKeluar;
            return $b;
        });

        $tglLaporan = $request->tgl_laporan ?? date('Y-m-d');
        $periode    = $request->semua_bulan ? 'Semua Periode'
            : Carbon::createFromDate(null, $request->bulan ?? date('n'), 1)->locale('id')->isoFormat('MMMM YYYY');

        // EXCEL
        if ($request->action === 'excel') {
            return Excel::download(
                new LaporanExport($barangs, $periode, $tglLaporan),
                'laporan-persediaan-' . str_replace(' ', '-', strtolower($periode)) . '.xlsx'
            );
        }

        // PDF
        if ($request->action === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf', compact('barangs', 'periode', 'tglLaporan'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('laporan-persediaan-' . str_replace(' ', '-', strtolower($periode)) . '.pdf');
        }

        // PREVIEW
        return view('laporan.index', compact('kategoris', 'barangs', 'periode', 'tglLaporan'));
    }
}

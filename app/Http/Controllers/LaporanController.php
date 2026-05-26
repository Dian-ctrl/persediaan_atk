<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // ── Halaman Laporan ────────────────────────────────────────────────────────
    public function index()
    {
        $kategoris = KategoriBarang::orderBy('kode')->get();
        return view('laporan.index', compact('kategoris'));
    }

    // ── Pengaturan Tanda Tangan ───────────────────────────────────────────────
    public function pengaturanTtd()
    {
        $pengaturan = [
            'ttd_mengetahui_nama'    => Pengaturan::get('ttd_mengetahui_nama'),
            'ttd_mengetahui_jabatan' => Pengaturan::get('ttd_mengetahui_jabatan'),
            'ttd_menyetujui_nama'    => Pengaturan::get('ttd_menyetujui_nama'),
            'ttd_menyetujui_jabatan' => Pengaturan::get('ttd_menyetujui_jabatan'),
            'ttd_membuat_nama'       => Pengaturan::get('ttd_membuat_nama'),
            'ttd_membuat_jabatan'    => Pengaturan::get('ttd_membuat_jabatan'),
            'nama_instansi'          => Pengaturan::get('nama_instansi', 'BPJS Ketenagakerjaan'),
            'kota'                   => Pengaturan::get('kota', 'Kudus'),
        ];
        return view('laporan.pengaturan-ttd', compact('pengaturan'));
    }

    public function simpanPengaturanTtd(Request $request)
    {
        $request->validate([
            'ttd_mengetahui_nama'    => 'required|string|max:200',
            'ttd_mengetahui_jabatan' => 'required|string|max:200',
            'ttd_menyetujui_nama'    => 'required|string|max:200',
            'ttd_menyetujui_jabatan' => 'required|string|max:200',
            'ttd_membuat_nama'       => 'required|string|max:200',
            'ttd_membuat_jabatan'    => 'required|string|max:200',
            'nama_instansi'          => 'required|string|max:200',
            'kota'                   => 'required|string|max:100',
        ]);

        foreach ([
            'ttd_mengetahui_nama', 'ttd_mengetahui_jabatan',
            'ttd_menyetujui_nama', 'ttd_menyetujui_jabatan',
            'ttd_membuat_nama',    'ttd_membuat_jabatan',
            'nama_instansi',       'kota',
        ] as $k) {
            Pengaturan::set($k, $request->input($k));
        }

        return redirect()->route('laporan.pengaturan-ttd')
                         ->with('success', 'Pengaturan tanda tangan berhasil disimpan.');
    }

    // ── Preview ───────────────────────────────────────────────────────────────
    public function preview(Request $request)
    {
        $barang    = $this->queryBarang($request);
        $kategoris = KategoriBarang::orderBy('kode')->get();
        return view('laporan.preview', compact('barang', 'kategoris'));
    }

    // ── Download Excel ─────────────────────────────────────────────────────────
    public function downloadExcel(Request $request)
    {
        $barang   = $this->queryBarang($request);
        $ttd      = $this->getTtd();
        $periode  = $this->labelPeriode($request);
        $tanggal  = $this->labelTanggal($request);
        $grouped  = $barang->groupBy(fn($b) => $b->kategori->kode ?? '-');
        $filename = 'laporan_persediaan_' . str_replace(' ', '_', strtolower($periode)) . '.xlsx';
        $filepath = storage_path('app/public/' . $filename);

        $this->buildExcel($grouped, $ttd, $periode, $tanggal, $filepath);

        return response()->download($filepath, $filename)->deleteFileAfterSend(true);
    }

    // ── Download PDF ───────────────────────────────────────────────────────────
    public function downloadPdf(Request $request)
    {
        $barang   = $this->queryBarang($request);
        $ttd      = $this->getTtd();
        $periode  = $this->labelPeriode($request);
        $tanggal  = $this->labelTanggal($request);
        $grouped  = $barang->groupBy(fn($b) => $b->kategori->kode ?? '-');
        $filename = 'laporan_persediaan_' . str_replace(' ', '_', strtolower($periode)) . '.pdf';
        $filepath = storage_path('app/public/' . $filename);

        $this->buildPdf($grouped, $ttd, $periode, $tanggal, $filepath);

        return response()->download($filepath, $filename)->deleteFileAfterSend(true);
    }

    // ── Private helpers ────────────────────────────────────────────────────────
    private function queryBarang(Request $request)
    {
        $q = Barang::with(['kategori', 'satuan'])->where('aktif', true);
        if ($request->filled('kategori_id')) {
            $q->where('kategori_id', $request->kategori_id);
        }

        $items = $q->orderBy('kategori_id')->orderBy('nama_barang')->get();

        // Determine date filtering for transactions
        $filterType = 'all'; // all months
        $from = null; $to = null; // date range

        if ($request->filled('all_months')) {
            $filterType = 'all';
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $filterType = 'month';
            $y = (int)$request->tahun;
            $m = (int)$request->bulan;
            $from = \Carbon\Carbon::create($y, $m, 1)->startOfDay();
            $to   = (clone $from)->endOfMonth()->endOfDay();
        } elseif ($request->filled('tahun')) {
            $filterType = 'year';
            $y = (int)$request->tahun;
            $from = \Carbon\Carbon::create($y, 1, 1)->startOfDay();
            $to   = (clone $from)->endOfYear()->endOfDay();
        }

        // attach computed totals (masuk/pakai) per item
        $items->transform(function ($b) use ($filterType, $from, $to) {
            $masukQ = \App\Models\TransaksiMasuk::where('barang_id', $b->id);
            $keluarQ = \App\Models\TransaksiKeluar::where('barang_id', $b->id);

            if ($filterType === 'month' || $filterType === 'year') {
                $masukQ->whereBetween('tanggal', [$from, $to]);
                $keluarQ->whereBetween('tanggal', [$from, $to]);
            }

            $b->masuk  = (float) $masukQ->sum('jumlah');
            $b->keluar = (float) $keluarQ->sum('jumlah');

            return $b;
        });

        return $items;
    }

    private function getTtd(): array
    {
        return [
            'mengetahui_nama'    => Pengaturan::get('ttd_mengetahui_nama',    'Kepala Cabang'),
            'mengetahui_jabatan' => Pengaturan::get('ttd_mengetahui_jabatan', 'Kepala Kantor Cabang'),
            'menyetujui_nama'    => Pengaturan::get('ttd_menyetujui_nama',    'Kabid'),
            'menyetujui_jabatan' => Pengaturan::get('ttd_menyetujui_jabatan', 'Kabid Pengendalian Operasional'),
            'membuat_nama'       => Pengaturan::get('ttd_membuat_nama',       'Pembuat'),
            'membuat_jabatan'    => Pengaturan::get('ttd_membuat_jabatan',    'Penata Operasional Cabang'),
            'kota'               => Pengaturan::get('kota',                   'Kudus'),
            'instansi'           => Pengaturan::get('nama_instansi',          'BPJS Ketenagakerjaan'),
        ];
    }

    private function labelPeriode(Request $request): string
    {
        $bulanNama = ['','Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];
        if ($request->filled('all_months')) {
            return 'Semua Bulan';
        }
        if ($request->filled('bulan') && $request->filled('tahun')) {
            return $bulanNama[(int)$request->bulan] . ' ' . $request->tahun;
        }
        if ($request->filled('tahun')) {
            return 'Tahun ' . $request->tahun;
        }
        return now()->locale('id')->isoFormat('MMMM YYYY');
    }

    private function labelTanggal(Request $request): string
    {
        if ($request->filled('tanggal_laporan')) {
            return \Carbon\Carbon::parse($request->tanggal_laporan)
                ->locale('id')->isoFormat('D MMMM YYYY');
        }
        return now()->locale('id')->isoFormat('D MMMM YYYY');
    }

    // ── Build Excel ────────────────────────────────────────────────────────────
    private function buildExcel($grouped, array $ttd, string $periode, string $tanggal, string $filepath): void
    {
        $wb = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ws = $wb->getActiveSheet();
        $ws->setTitle('Laporan');

        $HIJAU     = 'FF00873E';
        $HIJAU_BG  = 'FFE8F5EE';
        $SUB_BG    = 'FFD5EEDA';
        $WHITE     = 'FFFFFFFF';

        $styleBold   = ['font' => ['bold' => true]];
        $styleCenter = ['alignment' => ['horizontal' => 'center', 'vertical' => 'center']];
        $styleBorder = ['borders' => ['allBorders' => ['borderStyle' => 'thin']]];
        $styleHijau  = ['fill' => ['fillType' => 'solid', 'startColor' => ['argb' => $HIJAU]],
                        'font' => ['bold' => true, 'color' => ['argb' => $WHITE]]];

        // Widths
        foreach (['A'=>5,'B'=>12,'C'=>35,'D'=>10,'E'=>20,'F'=>8,'G'=>12,'H'=>12,'I'=>18,'J'=>20,'K'=>20] as $col=>$w) {
            $ws->getColumnDimension($col)->setWidth($w);
        }

        // Header
        $ws->mergeCells('A1:K1');
        $ws->setCellValue('A1', 'DAFTAR PERSEDIAAN BARANG');
        $ws->getStyle('A1')->applyFromArray(array_merge($styleBold, $styleCenter, ['font'=>['bold'=>true,'size'=>13]]));

        $ws->mergeCells('A2:K2');
        $ws->setCellValue('A2', 'Per ' . $periode);
        $ws->getStyle('A2')->applyFromArray(array_merge($styleBold, $styleCenter));

        $ws->mergeCells('A3:K3');
        $ws->setCellValue('A3', $ttd['instansi']);
        $ws->getStyle('A3')->applyFromArray($styleCenter);

        // Column headers
        $ws->getRowDimension(5)->setRowHeight(28);
        foreach ([
            'A5'=>'No','B5'=>'Kode','C5'=>'Nama Barang','D5'=>'Satuan',
            'E5'=>'Kategori','F5'=>'Stok','G5'=>'Penambahan','H5'=>'Pemakaian',
            'I5'=>'Harga Satuan (Rp)','J5'=>'Total Nilai (Rp)','K5'=>'Keterangan'
        ] as $cell=>$val) {
            $ws->setCellValue($cell, $val);
        }
        $ws->getStyle('A5:K5')->applyFromArray(array_merge($styleBold, $styleCenter, $styleBorder, $styleHijau));
        $ws->getStyle('A5:K5')->getAlignment()->setWrapText(true);

        $row = 6; $no = 1; $grandTotal = 0;

        foreach ($grouped as $kodeKategori => $items) {
            $katNama = $items->first()->kategori->nama ?? $kodeKategori;

            // Kategori header row
            $ws->mergeCells("A{$row}:K{$row}");
            $ws->setCellValue("A{$row}", "[ {$kodeKategori} ]  {$katNama}");
            $ws->getStyle("A{$row}:I{$row}")->applyFromArray(array_merge($styleBold, $styleBorder, [
                'fill' => ['fillType'=>'solid','startColor'=>['argb'=>$HIJAU_BG]],
                'font' => ['bold'=>true,'color'=>['argb'=>'FF00652E']],
            ]));
            $row++;

            $subtotal = 0;
            foreach ($items as $b) {
                $total = $b->stok * $b->harga_satuan;
                $subtotal   += $total;
                $grandTotal += $total;

                $ws->setCellValue("A{$row}", $no++);
                $ws->setCellValue("B{$row}", $b->kode_barang);
                $ws->setCellValue("C{$row}", $b->nama_barang);
                $ws->setCellValue("D{$row}", $b->satuan->nama ?? '-');
                $ws->setCellValue("E{$row}", $b->kategori->nama ?? '-');
                $ws->setCellValue("F{$row}", $b->stok);
                $ws->setCellValue("G{$row}", $b->masuk ?? 0);
                $ws->setCellValue("H{$row}", $b->keluar ?? 0);
                $ws->getCell("I{$row}")->setValueExplicit((float)$b->harga_satuan, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $ws->getCell("J{$row}")->setValueExplicit((float)$total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $ws->setCellValue("K{$row}", $b->keterangan ?? '');

                $ws->getStyle("I{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $ws->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0');
                $ws->getStyle("A{$row}:K{$row}")->applyFromArray($styleBorder);
                $ws->getStyle("A{$row}")->applyFromArray($styleCenter);
                $ws->getStyle("F{$row}:H{$row}")->applyFromArray($styleCenter);
                $row++;
            }

            // Subtotal row
            $ws->mergeCells("A{$row}:I{$row}");
            $ws->setCellValue("A{$row}", "Subtotal {$katNama}");
            $ws->getCell("J{$row}")->setValueExplicit((float)$subtotal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $ws->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0');
            $ws->getStyle("A{$row}:K{$row}")->applyFromArray(array_merge($styleBold, $styleBorder, [
                'fill' => ['fillType'=>'solid','startColor'=>['argb'=>$SUB_BG]],
            ]));
            $ws->getStyle("A{$row}")->applyFromArray(['alignment'=>['horizontal'=>'right']]);
            $row++;
        }

        // Grand total
        $ws->mergeCells("A{$row}:I{$row}");
        $ws->setCellValue("A{$row}", 'GRAND TOTAL');
        $ws->getCell("J{$row}")->setValueExplicit((float)$grandTotal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $ws->getStyle("J{$row}")->getNumberFormat()->setFormatCode('#,##0');
        $ws->getStyle("A{$row}:K{$row}")->applyFromArray(array_merge($styleBold, $styleBorder, $styleHijau));
        $ws->getStyle("A{$row}")->applyFromArray(['alignment'=>['horizontal'=>'right']]);

        // Tanda tangan
        $row += 2;
        $ws->mergeCells("A{$row}:K{$row}");
        $ws->setCellValue("A{$row}", $ttd['kota'] . ', ' . $tanggal);
        $ws->getStyle("A{$row}")->applyFromArray(['alignment'=>['horizontal'=>'right']]);

        $row++;
        $ttdRow = $row;
        $ws->setCellValue("A{$ttdRow}", 'Mengetahui,');
        $ws->setCellValue("D{$ttdRow}", 'Menyetujui,');
        $ws->setCellValue("G{$ttdRow}", 'Membuat,');

        $row += 4;
        $ws->setCellValue("A{$row}", $ttd['mengetahui_nama']);
        $ws->getStyle("A{$row}")->applyFromArray($styleBold);
        $ws->setCellValue("D{$row}", $ttd['menyetujui_nama']);
        $ws->getStyle("D{$row}")->applyFromArray($styleBold);
        $ws->setCellValue("G{$row}", $ttd['membuat_nama']);
        $ws->getStyle("G{$row}")->applyFromArray($styleBold);

        $row++;
        $ws->setCellValue("A{$row}", $ttd['mengetahui_jabatan']);
        $ws->setCellValue("D{$row}", $ttd['menyetujui_jabatan']);
        $ws->setCellValue("G{$row}", $ttd['membuat_jabatan']);

        @mkdir(dirname($filepath), 0755, true);
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($wb))->save($filepath);
    }

    // ── Build PDF ──────────────────────────────────────────────────────────────
    private function buildPdf($grouped, array $ttd, string $periode, string $tanggal, string $filepath): void
    {
        @mkdir(dirname($filepath), 0755, true);
        $html = $this->buildPdfHtml($grouped, $ttd, $periode, $tanggal);

        $dompdf = new \Dompdf\Dompdf(['defaultFont' => 'helvetica', 'isRemoteEnabled' => false]);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();
        file_put_contents($filepath, $dompdf->output());
    }

    private function buildPdfHtml($grouped, array $ttd, string $periode, string $tanggal): string
    {
        $grandTotal = 0;
        $rows = '';
        $no   = 1;

        foreach ($grouped as $kodeKategori => $items) {
            $katNama = $items->first()->kategori->nama ?? $kodeKategori;
                     $rows .= '<tr style="background:#e8f5ee"><td colspan="10" style="font-weight:bold;padding:5px 8px;color:#00652E">'
                     . htmlspecialchars("[{$kodeKategori}] {$katNama}") . '</td></tr>';

            $subtotal = 0;
            foreach ($items as $b) {
                $total       = $b->stok * $b->harga_satuan;
                $subtotal   += $total;
                $grandTotal += $total;
                $bg = ($no % 2 == 0) ? '#f7fdf9' : '#ffffff';
                $rows .= "<tr style=\"background:{$bg}\">"
                       . '<td style="text-align:center">' . $no++ . '</td>'
                       . '<td>' . htmlspecialchars($b->kode_barang) . '</td>'
                       . '<td>' . htmlspecialchars($b->nama_barang) . '</td>'
                       . '<td style="text-align:center">' . htmlspecialchars($b->satuan->nama ?? '-') . '</td>'
                       . '<td>' . htmlspecialchars($b->kategori->nama ?? '-') . '</td>'
                       . '<td style="text-align:center">' . number_format($b->stok, 0, ',', '.') . '</td>'
                           . '<td style="text-align:center">' . number_format($b->masuk ?? 0, 0, ',', '.') . '</td>'
                           . '<td style="text-align:center">' . number_format($b->keluar ?? 0, 0, ',', '.') . '</td>'
                           . '<td style="text-align:right">' . number_format($b->harga_satuan, 0, ',', '.') . '</td>'
                           . '<td style="text-align:right">' . number_format($total, 0, ',', '.') . '</td>'
                       . '</tr>';
            }
                $rows .= '<tr style="background:#d5eeda;font-weight:bold">'
                       . '<td colspan="9" style="text-align:right;padding:4px 8px">Subtotal ' . htmlspecialchars($katNama) . ':</td>'
                           . '<td style="text-align:right">' . number_format($subtotal, 0, ',', '.') . '</td>'
                       . '</tr>';
        }

            $rows .= '<tr style="background:#00873E;color:white;font-weight:bold">'
                       . '<td colspan="9" style="text-align:right;padding:5px 8px">GRAND TOTAL:</td>'
                       . '<td style="text-align:right">' . number_format($grandTotal, 0, ',', '.') . '</td>'
                   . '</tr>';

        return '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
body{font-family:Helvetica,Arial,sans-serif;font-size:9pt;margin:15px}
h2{text-align:center;margin:0 0 2px;font-size:13pt;color:#00652E}
h3{text-align:center;margin:0 0 2px;font-size:10pt}
p.instansi{text-align:center;margin:0 0 12px;font-size:9pt;color:#555}
table{width:100%;border-collapse:collapse;margin-top:4px}
th{background:#00873E;color:#fff;padding:6px 6px;font-size:8pt;border:1px solid #005c2a;text-align:center}
td{padding:4px 6px;border:1px solid #c6e8d5;font-size:8pt;vertical-align:middle}
.ttd{width:100%;margin-top:30px}
.ttd td{border:none;text-align:center;width:33%;vertical-align:top;padding-top:0}
.ttd-name{font-weight:bold;padding-top:48px;display:block}
</style></head><body>
<h2>DAFTAR PERSEDIAAN BARANG</h2>
<h3>Per ' . htmlspecialchars($periode) . '</h3>
<p class="instansi">' . htmlspecialchars($ttd['instansi']) . '</p>
<table>
<thead><tr>
    <th style="width:28px">No</th>
    <th style="width:75px">Kode</th>
    <th>Nama Barang</th>
    <th style="width:50px">Satuan</th>
    <th style="width:100px">Kategori</th>
    <th style="width:40px">Stok</th>
    <th style="width:60px">Penambahan</th>
    <th style="width:60px">Pemakaian</th>
    <th style="width:95px">Harga Satuan (Rp)</th>
    <th style="width:105px">Total Nilai (Rp)</th>
</tr></thead>
<tbody>' . $rows . '</tbody>
</table>
<p style="text-align:right;margin-top:16px;font-size:9pt">'
. htmlspecialchars($ttd['kota']) . ', ' . htmlspecialchars($tanggal) . '</p>
<table class="ttd"><tr>
  <td><span>Mengetahui,</span><span class="ttd-name">' . htmlspecialchars($ttd['mengetahui_nama']) . '</span><span>' . htmlspecialchars($ttd['mengetahui_jabatan']) . '</span></td>
  <td><span>Menyetujui,</span><span class="ttd-name">' . htmlspecialchars($ttd['menyetujui_nama']) . '</span><span>' . htmlspecialchars($ttd['menyetujui_jabatan']) . '</span></td>
  <td><span>Membuat,</span><span class="ttd-name">' . htmlspecialchars($ttd['membuat_nama']) . '</span><span>' . htmlspecialchars($ttd['membuat_jabatan']) . '</span></td>
</tr></table>
</body></html>';
    }
}

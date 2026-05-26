<?php

namespace App\Http\Controllers\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Collection;

class LaporanExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected Collection $barangs;
    protected string $periode;
    protected string $tglLaporan;

    public function __construct(Collection $barangs, string $periode, string $tglLaporan)
    {
        $this->barangs    = $barangs;
        $this->periode    = $periode;
        $this->tglLaporan = $tglLaporan;
    }

    public function collection(): Collection
    {
        return $this->barangs->map(function ($b, $i) {
            return [
                'no'           => $i + 1,
                'kode'         => $b->kode,
                'nama_barang'  => $b->nama_barang,
                'kategori'     => $b->kategori->nama_kategori ?? '-',
                'satuan'       => $b->satuan->nama_satuan ?? '-',
                'stok_awal'    => $b->stok_awal ?? 0,
                'masuk'        => $b->total_masuk ?? 0,
                'keluar'       => $b->total_keluar ?? 0,
                'stok_akhir'   => $b->stok,
                'harga_satuan' => $b->harga_satuan,
                'total_nilai'  => $b->stok * $b->harga_satuan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PERSEDIAAN BARANG – BALAI DESA MEDINI'],
            ['Periode: ' . $this->periode . '  |  Tanggal Laporan: ' . \Carbon\Carbon::parse($this->tglLaporan)->format('d/m/Y')],
            [''],
            [
                'No', 'Kode', 'Nama Barang', 'Kategori', 'Satuan',
                'Stok Awal', 'Masuk', 'Keluar', 'Stok Akhir',
                'Harga Satuan', 'Total Nilai',
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Persediaan';
    }

    public function styles(Worksheet $sheet): array
    {
        // Merge title rows
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->mergeCells('A3:K3');

        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1B4332']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font'      => ['size' => 11, 'color' => ['rgb' => '2D6A4F']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1B4332'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}

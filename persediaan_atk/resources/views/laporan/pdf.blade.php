<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap');

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1a2a1e; }

    .header {
        background: #1B4332;
        color: white;
        padding: 12px 16px;
        margin-bottom: 12px;
    }
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header h1 { font-size: 14px; font-weight: 700; letter-spacing: 0.5px; }
    .header h2 { font-size: 10px; font-weight: 400; opacity: 0.8; margin-top: 2px; }
    .header-right { text-align: right; font-size: 9px; opacity: 0.85; line-height: 1.8; }

    .meta-bar {
        background: #D8F3DC;
        border: 1px solid #74C69D;
        border-radius: 4px;
        padding: 6px 12px;
        margin-bottom: 10px;
        display: flex;
        gap: 24px;
        font-size: 9px;
    }
    .meta-bar strong { color: #1B4332; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8.5px;
    }
    thead tr {
        background: #2D6A4F;
        color: white;
    }
    thead th {
        padding: 6px 6px;
        text-align: left;
        font-weight: 700;
        font-size: 8px;
        letter-spacing: 0.3px;
        border: 1px solid #1B4332;
    }
    thead th.center { text-align: center; }
    thead th.right  { text-align: right; }

    tbody tr:nth-child(even) { background: #f5faf6; }
    tbody tr { border-bottom: 1px solid #c8dbc8; }
    tbody td { padding: 5px 6px; vertical-align: middle; border: 1px solid #d8e8d8; }
    tbody td.center { text-align: center; }
    tbody td.right  { text-align: right; }

    .kat-badge {
        background: #D8F3DC;
        color: #1B4332;
        border-radius: 10px;
        padding: 1px 6px;
        font-size: 7.5px;
        font-weight: 700;
        display: inline-block;
    }
    .kode { font-family: 'Courier New', monospace; color: #2D6A4F; font-weight: 700; font-size: 8px; }
    .stok-ok  { background: #D8F3DC; color: #1B4332; border-radius: 3px; padding: 1px 5px; font-weight: 700; }
    .stok-low { background: #FDEBD0; color: #E67E22; border-radius: 3px; padding: 1px 5px; font-weight: 700; }
    .stok-nil { background: #FADBD8; color: #C0392B; border-radius: 3px; padding: 1px 5px; font-weight: 700; }

    tfoot tr {
        background: #D8F3DC;
        font-weight: 700;
    }
    tfoot td {
        padding: 6px 6px;
        border: 1px solid #74C69D;
        color: #1B4332;
    }

    .footer {
        margin-top: 14px;
        display: flex;
        justify-content: flex-end;
    }
    .ttd {
        text-align: center;
        font-size: 9px;
        width: 200px;
    }
    .ttd .label { font-weight: 700; color: #1B4332; }
    .ttd .space { height: 50px; border-bottom: 1px solid #333; margin: 6px 0; }
    .ttd .nama  { font-weight: 700; }
    .ttd .jabatan { color: #666; font-size: 8px; }
    .page-no { text-align: center; color: #888; font-size: 8px; margin-top: 8px; }
</style>
</head>
<body>

<div class="header">
    <div class="header-top">
        <div>
            <h1>LAPORAN PERSEDIAAN BARANG</h1>
            <h2>Balai Desa Medini &bull; Sistem Stok Opname</h2>
        </div>
        <div class="header-right">
            <div>Periode: <strong style="color:#F4D03F">{{ $periode }}</strong></div>
            <div>Tanggal Laporan: {{ \Carbon\Carbon::parse($tglLaporan)->format('d F Y') }}</div>
            <div>Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB</div>
        </div>
    </div>
</div>

@php
    $totalNilai  = $barangs->sum(fn($b) => $b->stok * $b->harga_satuan);
    $totalMasuk  = $barangs->sum(fn($b) => $b->total_masuk ?? 0);
    $totalKeluar = $barangs->sum(fn($b) => $b->total_keluar ?? 0);
@endphp

<div class="meta-bar">
    <span><strong>Total Jenis:</strong> {{ $barangs->count() }} item</span>
    <span><strong>Total Masuk:</strong> {{ number_format($totalMasuk,0,',','.') }}</span>
    <span><strong>Total Keluar:</strong> {{ number_format($totalKeluar,0,',','.') }}</span>
    <span><strong>Nilai Persediaan:</strong> Rp {{ number_format($totalNilai,0,',','.') }}</span>
</div>

<table>
    <thead>
        <tr>
            <th style="width:24px" class="center">No</th>
            <th style="width:55px">Kode</th>
            <th>Nama Barang</th>
            <th style="width:80px">Kategori</th>
            <th style="width:35px" class="center">Satuan</th>
            <th style="width:38px" class="center">Stok Awal</th>
            <th style="width:32px" class="center">Masuk</th>
            <th style="width:32px" class="center">Keluar</th>
            <th style="width:42px" class="center">Stok Akhir</th>
            <th style="width:72px" class="right">Harga Satuan</th>
            <th style="width:85px" class="right">Total Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($barangs as $i => $b)
        <tr>
            <td class="center">{{ $i+1 }}</td>
            <td><span class="kode">{{ $b->kode }}</span></td>
            <td>{{ $b->nama_barang }}</td>
            <td><span class="kat-badge">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
            <td class="center">{{ $b->satuan->nama_satuan ?? '-' }}</td>
            <td class="center">{{ $b->stok_awal ?? 0 }}</td>
            <td class="center" style="color:#2D6A4F;font-weight:600">+{{ $b->total_masuk ?? 0 }}</td>
            <td class="center" style="color:#C0392B;font-weight:600">-{{ $b->total_keluar ?? 0 }}</td>
            <td class="center">
                @php $stok = $b->stok; @endphp
                <span class="{{ $stok == 0 ? 'stok-nil' : ($stok <= 5 ? 'stok-low' : 'stok-ok') }}">{{ $stok }}</span>
            </td>
            <td class="right">Rp {{ number_format($b->harga_satuan,0,',','.') }}</td>
            <td class="right" style="font-weight:700;color:#2D6A4F">Rp {{ number_format($b->stok * $b->harga_satuan,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9" class="right">TOTAL NILAI PERSEDIAAN</td>
            <td></td>
            <td class="right" style="font-size:10px;color:#1B4332">Rp {{ number_format($totalNilai,0,',','.') }}</td>
        </tr>
    </tfoot>
</table>

<div class="footer">
    <div class="ttd">
        <div class="label">Mengetahui,</div>
        <div style="font-size:8px;color:#666">Kepala Desa Medini</div>
        <div class="space"></div>
        <div class="nama">________________________</div>
        <div class="jabatan">Kepala Desa</div>
    </div>
</div>

<div class="page-no">
    Halaman 1 – Laporan dicetak oleh Sistem Stok Opname Balai Desa Medini
</div>

</body>
</html>

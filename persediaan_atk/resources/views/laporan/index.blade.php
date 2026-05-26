@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </div>
    <div>
        <h1>Laporan Persediaan</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <span>Laporan Persediaan</span>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Unduh Laporan Persediaan Barang
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.index') }}" style="display:flex;gap:16px;align-items:flex-end;flex-wrap:wrap">
            <div class="filter-group">
                <label class="filter-label">Bulan</label>
                <select name="bulan" class="form-control" style="min-width:130px">
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                    <option value="{{ $i+1 }}" {{ request('bulan', date('n')) == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Tahun</label>
                <select name="tahun" class="form-control" style="min-width:110px">
                    @for($y = date('Y'); $y >= date('Y')-5; $y--)
                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Tanggal Laporan</label>
                <input type="date" name="tgl_laporan" class="form-control" value="{{ request('tgl_laporan', date('Y-m-d')) }}">
            </div>
            <div class="filter-group">
                <label class="filter-label">Kategori (opsional)</label>
                <select name="kategori_id" class="form-control" style="min-width:180px">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end;margin-bottom:18px">
                <div style="display:flex;align-items:center;gap:6px;margin-right:8px">
                    <input type="checkbox" name="semua_bulan" id="semuaBulan" value="1" {{ request('semua_bulan') ? 'checked' : '' }}>
                    <label for="semuaBulan" style="font-size:12px;color:var(--text-light)">Unduh semua bulan (abaikan pilihan bulan/tahun)</label>
                </div>
                <button type="submit" name="action" value="preview" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview
                </button>
                <button type="submit" name="action" value="excel" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                </button>
                <button type="submit" name="action" value="pdf" class="btn btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF
                </button>
            </div>
        </form>
    </div>
</div>

<!-- PANDUAN -->
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <div class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Panduan
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
            <div style="background:var(--primary-pale);border:1px solid var(--border);border-radius:var(--radius);padding:16px">
                <div style="font-weight:700;color:var(--primary);margin-bottom:8px;display:flex;align-items:center;gap:8px">
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview
                </div>
                <p style="font-size:13px;color:var(--text-light)">Lihat data laporan terlebih dahulu sebelum mengunduh.</p>
            </div>
            <div style="background:#F0FFF4;border:1px solid #9AE6B4;border-radius:var(--radius);padding:16px">
                <div style="font-weight:700;color:#276749;margin-bottom:8px;display:flex;align-items:center;gap:8px">
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel (.xlsx)
                </div>
                <p style="font-size:13px;color:var(--text-light)">Unduh laporan format spreadsheet yang bisa diedit.</p>
            </div>
            <div style="background:#FFF5F5;border:1px solid #FEB2B2;border-radius:var(--radius);padding:16px">
                <div style="font-weight:700;color:#C53030;margin-bottom:8px;display:flex;align-items:center;gap:8px">
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    PDF
                </div>
                <p style="font-size:13px;color:var(--text-light)">Unduh laporan format PDF siap cetak (landscape A4).</p>
            </div>
        </div>
    </div>
</div>

@if(request('action') === 'preview' && isset($barangs))
<!-- PREVIEW TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Preview Laporan – {{ request('semua_bulan') ? 'Semua Periode' : \Carbon\Carbon::createFromDate(null, request('bulan'), 1)->locale('id')->isoFormat('MMMM') . ' ' . request('tahun') }}
        </div>
        <div style="font-size:12px;color:var(--text-muted)">Tanggal Laporan: {{ \Carbon\Carbon::parse(request('tgl_laporan'))->format('d/m/Y') }}</div>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE</th>
                    <th>NAMA BARANG</th>
                    <th>KATEGORI</th>
                    <th>SATUAN</th>
                    <th>STOK AWAL</th>
                    <th>MASUK</th>
                    <th>KELUAR</th>
                    <th>STOK AKHIR</th>
                    <th>HARGA SATUAN</th>
                    <th>TOTAL NILAI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $i => $b)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><span class="code-text">{{ $b->kode }}</span></td>
                    <td style="font-weight:600">{{ $b->nama_barang }}</td>
                    <td><span class="badge badge-green">{{ $b->kategori->nama_kategori ?? '-' }}</span></td>
                    <td>{{ $b->satuan->nama_satuan ?? '-' }}</td>
                    <td>{{ $b->stok_awal ?? 0 }}</td>
                    <td class="total-green">+{{ $b->total_masuk ?? 0 }}</td>
                    <td class="total-red">-{{ $b->total_keluar ?? 0 }}</td>
                    <td>
                        <span class="stok-num {{ $b->stok == 0 ? 'empty' : ($b->stok <= 5 ? 'low' : 'ok') }}">
                            {{ $b->stok }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($b->harga_satuan,0,',','.') }}</td>
                    <td class="total-green">Rp {{ number_format($b->stok * $b->harga_satuan,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:var(--primary-pale);font-weight:700">
                    <td colspan="10" style="text-align:right;padding:12px 14px;color:var(--primary-dark)">TOTAL NILAI PERSEDIAAN</td>
                    <td style="padding:12px 14px;color:var(--primary);font-family:'Cinzel',serif">Rp {{ number_format($barangs->sum(fn($b) => $b->stok * $b->harga_satuan),0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif
@endsection

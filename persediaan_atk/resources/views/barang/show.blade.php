@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <div>
        <h1>Detail Barang</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <a href="{{ route('barang.index') }}">Data Barang</a>
            <span class="sep">/</span>
            <span>{{ $barang->nama_barang }}</span>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:360px 1fr;gap:20px;align-items:start">

    <!-- INFO BARANG -->
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Barang
                </div>
                <a href="{{ route('barang.edit', $barang) }}" class="btn btn-warning btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
            </div>
            <div class="card-body" style="padding:0">
                <div class="summary-row" style="padding:12px 20px">
                    <span class="summary-label">Kode</span>
                    <span class="code-text">{{ $barang->kode }}</span>
                </div>
                <div class="summary-row" style="padding:12px 20px">
                    <span class="summary-label">Nama Barang</span>
                    <span class="summary-value">{{ $barang->nama_barang }}</span>
                </div>
                <div class="summary-row" style="padding:12px 20px">
                    <span class="summary-label">Kategori</span>
                    <span class="badge badge-green">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
                </div>
                <div class="summary-row" style="padding:12px 20px">
                    <span class="summary-label">Satuan</span>
                    <span class="summary-value">{{ $barang->satuan->nama_satuan ?? '-' }}</span>
                </div>
                <div class="summary-row" style="padding:12px 20px">
                    <span class="summary-label">Harga Satuan</span>
                    <span class="summary-value">Rp {{ number_format($barang->harga_satuan,0,',','.') }}</span>
                </div>
                <div class="summary-row" style="padding:12px 20px">
                    <span class="summary-label">Stok</span>
                    <span class="stok-num {{ $barang->stok == 0 ? 'empty' : ($barang->stok <= 5 ? 'low' : 'ok') }}">
                        {{ $barang->stok }} {{ $barang->satuan->nama_satuan ?? '' }}
                    </span>
                </div>
                <div class="summary-row" style="padding:12px 20px">
                    <span class="summary-label">Total Nilai</span>
                    <span class="summary-value highlight">Rp {{ number_format($barang->stok * $barang->harga_satuan,0,',','.') }}</span>
                </div>
                @if($barang->keterangan)
                <div style="padding:12px 20px;border-top:1px solid var(--border)">
                    <div class="form-label" style="margin-bottom:4px">Keterangan</div>
                    <p style="font-size:13px;color:var(--text-light)">{{ $barang->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <a href="{{ route('barang-masuk.create') }}?barang_id={{ $barang->id }}" class="btn btn-primary" style="flex:1;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                + Masuk
            </a>
            <a href="{{ route('barang-keluar.create') }}?barang_id={{ $barang->id }}" class="btn btn-danger" style="flex:1;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                - Keluar
            </a>
        </div>
    </div>

    <!-- RIWAYAT TRANSAKSI -->
    <div style="display:flex;flex-direction:column;gap:16px">
        <!-- MASUK -->
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="color:var(--primary)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Riwayat Barang Masuk ({{ $barang->transaksiMasuk->count() }})
                </div>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>TANGGAL</th>
                            <th>PERIODE</th>
                            <th>JUMLAH</th>
                            <th>HARGA SATUAN</th>
                            <th>TOTAL</th>
                            <th>NO. DOK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang->transaksiMasuk->sortByDesc('tanggal')->take(10) as $t)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                            <td><span class="badge badge-green">{{ $t->periode }}</span></td>
                            <td><span class="tx-num tx-masuk">+{{ $t->jumlah }}</span></td>
                            <td>Rp {{ number_format($t->harga_satuan,0,',','.') }}</td>
                            <td class="total-green">Rp {{ number_format($t->total,0,',','.') }}</td>
                            <td style="color:var(--text-muted);font-size:12px">{{ $t->no_dokumen ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;padding:20px;color:var(--text-muted)">Belum ada transaksi masuk</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- KELUAR -->
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="color:var(--danger)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Riwayat Barang Keluar ({{ $barang->transaksiKeluar->count() }})
                </div>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>TANGGAL</th>
                            <th>PERIODE</th>
                            <th>JUMLAH</th>
                            <th>HARGA SATUAN</th>
                            <th>TOTAL</th>
                            <th>PENERIMA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang->transaksiKeluar->sortByDesc('tanggal')->take(10) as $t)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                            <td><span class="badge badge-green">{{ $t->periode }}</span></td>
                            <td><span class="tx-num tx-keluar">-{{ $t->jumlah }}</span></td>
                            <td>Rp {{ number_format($t->harga_satuan,0,',','.') }}</td>
                            <td class="total-red">Rp {{ number_format($t->total,0,',','.') }}</td>
                            <td style="color:var(--text-light)">{{ $t->penerima ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;padding:20px;color:var(--text-muted)">Belum ada transaksi keluar</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

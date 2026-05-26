@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    </div>
    <div>
        <h1>Dashboard</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <span>Dashboard</span>
        </div>
    </div>
</div>

<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Jenis Barang</div>
            <div class="stat-value">{{ number_format($totalJenis) }}</div>
            <div class="stat-sub">Jenis barang</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Stok</div>
            <div class="stat-value">{{ number_format($totalStok) }}</div>
            <div class="stat-sub">Unit tersedia</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Nilai Persediaan</div>
            <div class="stat-value" style="font-size:18px">Rp {{ number_format($nilaiPersediaan,0,',','.') }}</div>
            <div class="stat-sub">Total inventori</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Stok Habis</div>
            <div class="stat-value">{{ $stokHabis }}</div>
            <div class="stat-sub">Item perlu restock</div>
        </div>
    </div>
</div>

<!-- BOTTOM ROW -->
<div style="display:grid;grid-template-columns:1fr 1fr 300px;gap:18px">

    <!-- NILAI PER KATEGORI -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                Nilai per Kategori
            </div>
        </div>
        <div class="card-body">
            @php $maxNilai = $nilaiKategori->max('total_nilai') ?: 1; @endphp
            @foreach($nilaiKategori as $kat)
            <div class="category-bar-item">
                <div class="cat-bar-header">
                    <span class="cat-bar-name">{{ $kat->nama_kategori }}</span>
                    <span class="cat-bar-value">Rp {{ number_format($kat->total_nilai,0,',','.') }}</span>
                </div>
                <div class="cat-bar-track">
                    <div class="cat-bar-fill" style="width:{{ ($kat->total_nilai/$maxNilai)*100 }}%"></div>
                </div>
                <div class="cat-bar-sub">{{ $kat->jml_barang }} jenis barang &middot; {{ round(($kat->total_nilai/max($nilaiPersediaan,1))*100,1) }}%</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- AKSES CEPAT -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Akses Cepat
            </div>
        </div>
        <div class="card-body">
            <a href="{{ route('barang.create') }}" class="quick-btn primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Barang
            </a>
            <a href="{{ route('barang-masuk.create') }}" class="quick-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Input Barang Masuk
            </a>
            <a href="{{ route('barang-keluar.create') }}" class="quick-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Input Barang Keluar
            </a>
            <a href="{{ route('laporan.index') }}" class="quick-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Cetak Laporan
            </a>
        </div>
    </div>

    <!-- RINGKASAN -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Ringkasan
            </div>
        </div>
        <div class="card-body" style="padding:16px 20px">
            <div class="summary-row">
                <span class="summary-label">Total Kategori</span>
                <span class="summary-value">{{ $totalKategori }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Satuan</span>
                <span class="summary-value">{{ $totalSatuan }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tx Masuk Bulan Ini</span>
                <span class="summary-value">{{ $txMasukBulan }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tx Keluar Bulan Ini</span>
                <span class="summary-value">{{ $txKeluarBulan }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Periode</span>
                <span class="summary-value highlight">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM YYYY') }}</span>
            </div>
        </div>
    </div>

</div>
@endsection

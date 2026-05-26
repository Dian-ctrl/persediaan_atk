@extends('layouts.pengguna')
@php $pageTitle = 'Dashboard ATK'; @endphp

@section('content')

<div class="row g-3 mb-4">
    {{-- Hero --}}
    <div class="col-lg-8">
        <div class="hero-banner">
            <h2>Manajemen ATK<br>Balai Desa Medini</h2>
            <p>Pantau dan kelola persediaan barang habis pakai secara real-time.</p>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('pengguna.transaksi-masuk.index') }}" class="hero-btn"><i class="bi bi-arrow-up-right-circle-fill"></i> Riwayat Keluar</a>
                <a href="{{ route('pengguna.transaksi-keluar.create') }}" class="hero-btn"><i class="bi bi-plus-circle-fill"></i> Proses Permintaan</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="status-card">
            <h5>STATUS INVENTARIS</h5>
            <h3>Kendali Cepat &amp; Presisi</h3>
            <p>Permintaan ditinjau dalam 12 jam. Proses terautomasi.</p>
            <div class="mt-3" style="display:flex;align-items:center;gap:8px">
                <div style="width:8px;height:8px;border-radius:50%;background:#7fffb0;flex-shrink:0"></div>
                <small style="color:rgba(255,255,255,0.8);font-size:.75rem">Sistem berjalan normal</small>
            </div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="bi bi-archive-fill"></i></div>
            <div>
                <div class="stat-label">Total Jenis Barang</div>
                <div class="stat-val">{{ $totalBarang }}</div>
                <div class="stat-sub">Item tersedia</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon si-teal"><i class="bi bi-layers-fill"></i></div>
            <div>
                <div class="stat-label">Total Stok</div>
                <div class="stat-val">{{ number_format($totalStok) }}</div>
                <div class="stat-sub">Unit di gudang</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="stat-label">Stok Habis</div>
                <div class="stat-val" style="color:#e65100">{{ $stokHabis }}</div>
                <div class="stat-sub" style="color:#e65100">Perlu restock</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Aksi Cepat --}}
    <div class="col-lg-4">
        <div class="card-md h-100">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-lightning-charge-fill"></i> Aksi Cepat</div>
            </div>
            <div class="card-md-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('pengguna.transaksi-masuk.create') }}" class="btn btn-green">
                        <i class="bi bi-box-arrow-in-down-right me-2"></i>Input Barang Masuk
                    </a>
                    <a href="{{ route('pengguna.transaksi-keluar.create') }}" class="btn btn-green-outline">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Input Barang Keluar
                    </a>
                    <a href="{{ route('pengguna.barang.index') }}" class="btn btn-green-outline">
                        <i class="bi bi-archive me-2"></i>Lihat Data Barang
                    </a>
                </div>
                <div class="mt-3 p-3" style="background:var(--green-light);border-radius:8px;border:1px solid var(--green-border);font-size:.78rem;color:#555">
                    <i class="bi bi-shield-check me-1" style="color:var(--green)"></i>
                    Stok barang diperbarui <strong>secara otomatis</strong> setiap kali Anda menyimpan transaksi.
                </div>
            </div>
        </div>
    </div>

    {{-- Masuk Terakhir --}}
    <div class="col-lg-4">
        <div class="card-md h-100">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-box-arrow-in-down-right"></i> Masuk Terakhir</div>
                <a href="{{ route('pengguna.transaksi-masuk.index') }}" style="font-size:.78rem;color:var(--green);text-decoration:none;font-weight:600">Lihat semua →</a>
            </div>
            <div class="table-responsive">
                <table class="table tbl-md">
                    <thead><tr><th>Tanggal</th><th>Barang</th><th>Jml</th></tr></thead>
                    <tbody>
                        @forelse($txMasukTerakhir as $tx)
                        <tr>
                            <td style="font-size:.78rem">{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                            <td style="font-size:.82rem;font-weight:600">{{ Str::limit($tx->barang->nama_barang, 24) }}</td>
                            <td><span class="badge-green">+{{ $tx->jumlah }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3" style="color:#aaa;font-size:.83rem">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Keluar Terakhir --}}
    <div class="col-lg-4">
        <div class="card-md h-100">
            <div class="card-md-header">
                <div class="card-md-title"><i class="bi bi-box-arrow-up-right"></i> Keluar Terakhir</div>
                <a href="{{ route('pengguna.transaksi-keluar.index') }}" style="font-size:.78rem;color:var(--green);text-decoration:none;font-weight:600">Lihat semua →</a>
            </div>
            <div class="table-responsive">
                <table class="table tbl-md">
                    <thead><tr><th>Tanggal</th><th>Barang</th><th>Jml</th></tr></thead>
                    <tbody>
                        @forelse($txKeluarTerakhir as $tx)
                        <tr>
                            <td style="font-size:.78rem">{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                            <td style="font-size:.82rem;font-weight:600">{{ Str::limit($tx->barang->nama_barang, 24) }}</td>
                            <td><span class="badge-red">-{{ $tx->jumlah }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3" style="color:#aaa;font-size:.83rem">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

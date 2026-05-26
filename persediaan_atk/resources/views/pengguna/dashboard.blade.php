@extends('layouts.pengguna')
@php $pageTitle = 'Dashboard'; $pageIcon = 'speedometer2'; @endphp

@section('content')

<div class="info-banner">
    <i class="bi bi-info-circle-fill" style="font-size:1.1rem;color:var(--biru)"></i>
    <div>
        Anda masuk sebagai <strong>Pengguna</strong>. Catat barang masuk atau keluar —
        <strong>stok otomatis diperbarui</strong> tanpa perlu login.
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="bi bi-archive-fill"></i></div>
            <div>
                <div class="stat-label">Total Jenis Barang</div>
                <div class="stat-val">{{ $totalBarang }}</div>
                <div class="stat-sub"><i class="bi bi-box me-1"></i>Item tersedia</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="bi bi-layers-fill"></i></div>
            <div>
                <div class="stat-label">Total Stok</div>
                <div class="stat-val">{{ number_format($totalStok) }}</div>
                <div class="stat-sub"><i class="bi bi-boxes me-1"></i>Unit di gudang</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="stat-label">Stok Habis</div>
                <div class="stat-val">{{ $stokHabis }}</div>
                <div class="stat-sub" style="color:#c2410c"><i class="bi bi-x-circle me-1"></i>Perlu restock</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Aksi Cepat --}}
    <div class="col-lg-4">
        <div class="card-biru h-100">
            <div class="card-header-biru">
                <div class="title"><i class="bi bi-lightning-charge-fill"></i> Aksi Cepat</div>
            </div>
            <div class="card-body-biru">
                <div class="d-grid gap-2">
                    <a href="{{ route('pengguna.transaksi-masuk.create') }}" class="btn btn-biru">
                        <i class="bi bi-box-arrow-in-down-right me-2"></i>Input Barang Masuk
                    </a>
                    <a href="{{ route('pengguna.transaksi-keluar.create') }}" class="btn btn-biru-outline">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Input Barang Keluar
                    </a>
                    <a href="{{ route('pengguna.barang.index') }}" class="btn btn-biru-outline">
                        <i class="bi bi-archive me-2"></i>Lihat Data Barang
                    </a>
                </div>
                <div class="mt-3 p-3" style="background:#f8faff;border-radius:8px;border:1px solid var(--biru-border);font-size:.78rem;color:#555">
                    <i class="bi bi-shield-check me-1" style="color:var(--biru)"></i>
                    Stok barang diperbarui <strong>secara otomatis</strong> setiap kali Anda menyimpan transaksi.
                </div>
            </div>
        </div>
    </div>

    {{-- Masuk Terakhir --}}
    <div class="col-lg-4">
        <div class="card-biru h-100">
            <div class="card-header-biru">
                <div class="title"><i class="bi bi-box-arrow-in-down-right"></i> Masuk Terakhir</div>
                <a href="{{ route('pengguna.transaksi-masuk.index') }}" style="font-size:.78rem;color:var(--biru);text-decoration:none">Lihat semua →</a>
            </div>
            <div class="table-responsive">
                <table class="table tbl-biru">
                    <thead><tr><th>Tanggal</th><th>Barang</th><th>Jml</th></tr></thead>
                    <tbody>
                        @forelse($txMasukTerakhir as $tx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                            <td style="font-size:.82rem">{{ Str::limit($tx->barang->nama_barang, 24) }}</td>
                            <td><span class="badge-hijau">+{{ $tx->jumlah }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3" style="font-size:.83rem">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Keluar Terakhir --}}
    <div class="col-lg-4">
        <div class="card-biru h-100">
            <div class="card-header-biru">
                <div class="title"><i class="bi bi-box-arrow-up-right"></i> Keluar Terakhir</div>
                <a href="{{ route('pengguna.transaksi-keluar.index') }}" style="font-size:.78rem;color:var(--biru);text-decoration:none">Lihat semua →</a>
            </div>
            <div class="table-responsive">
                <table class="table tbl-biru">
                    <thead><tr><th>Tanggal</th><th>Barang</th><th>Jml</th></tr></thead>
                    <tbody>
                        @forelse($txKeluarTerakhir as $tx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                            <td style="font-size:.82rem">{{ Str::limit($tx->barang->nama_barang, 24) }}</td>
                            <td><span class="badge-merah">-{{ $tx->jumlah }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3" style="font-size:.83rem">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

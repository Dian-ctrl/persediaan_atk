@extends('layouts.app')
@php $pageTitle = 'Dashboard'; $pageIcon = 'speedometer2'; @endphp

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="bi bi-archive-fill"></i></div>
            <div>
                <div class="stat-label">Total Jenis Barang</div>
                <div class="stat-val">{{ $totalBarang }}</div>
                <div class="stat-sub"><i class="bi bi-box me-1"></i>Jenis barang</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-teal"><i class="bi bi-layers-fill"></i></div>
            <div>
                <div class="stat-label">Total Stok</div>
                <div class="stat-val">{{ number_format($totalStok) }}</div>
                <div class="stat-sub"><i class="bi bi-boxes me-1"></i>Unit tersedia</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-lime"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-label">Nilai Persediaan</div>
                <div class="stat-val" style="font-size:1.1rem">Rp {{ number_format($totalNilai,0,',','.') }}</div>
                <div class="stat-sub"><i class="bi bi-graph-up me-1"></i>Total inventori</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="stat-label">Stok Habis</div>
                <div class="stat-val">{{ $stokHabis }}</div>
                <div class="stat-sub" style="color:#e65100"><i class="bi bi-x-circle me-1"></i>Item perlu restock</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Stok per Kategori --}}
    <div class="col-lg-5">
        <div class="card-hijau h-100">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-pie-chart-fill"></i> Nilai per Kategori</div>
            </div>
            <div class="card-body-hijau">
                @foreach($nilaiPerKategori as $kat)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1" style="font-size:.83rem">
                        <span class="fw-600">{{ $kat->nama }}</span>
                        <span class="fw-bold" style="color:var(--hijau)">Rp {{ number_format($kat->total_nilai,0,',','.') }}</span>
                    </div>
                    @php
                        $pct = $totalNilai > 0 ? ($kat->total_nilai / $totalNilai) * 100 : 0;
                    @endphp
                    <div class="progress" style="height:7px;border-radius:10px;background:#e3f7ec">
                        <div class="progress-bar" style="width:{{ $pct }}%;background:var(--hijau);border-radius:10px"></div>
                    </div>
                    <small style="color:#888">{{ $kat->jumlah_barang }} jenis barang · {{ number_format($pct,1) }}%</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Akses Cepat --}}
    <div class="col-lg-4">
        <div class="card-hijau h-100">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-lightning-charge-fill"></i> Akses Cepat</div>
            </div>
            <div class="card-body-hijau">
                <div class="d-grid gap-2">
                    <a href="{{ route('barang.create') }}" class="btn btn-hijau">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Barang
                    </a>
                    <a href="{{ route('transaksi-masuk.create') }}" class="btn btn-hijau-outline">
                        <i class="bi bi-box-arrow-in-down-right me-2"></i>Input Barang Masuk
                    </a>
                    <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-hijau-outline">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Input Barang Keluar
                    </a>
                 
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="col-lg-3">
        <div class="card-hijau h-100">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-info-circle-fill"></i> Ringkasan</div>
            </div>
            <div class="card-body-hijau p-0">
                <table class="table table-borderless mb-0" style="font-size:.84rem">
                    <tr>
                        <td class="ps-3 text-muted">Total Kategori</td>
                        <td class="fw-bold text-end pe-3" style="color:var(--hijau)">{{ $totalKategori }}</td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="ps-3 text-muted">Total Satuan</td>
                        <td class="fw-bold text-end pe-3" style="color:var(--hijau)">{{ $totalSatuan }}</td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="ps-3 text-muted">Tx Masuk Bulan Ini</td>
                        <td class="fw-bold text-end pe-3" style="color:var(--hijau)">{{ $txMasukBulanIni }}</td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="ps-3 text-muted">Tx Keluar Bulan Ini</td>
                        <td class="fw-bold text-end pe-3" style="color:var(--hijau)">{{ $txKeluarBulanIni }}</td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="ps-3 text-muted">Periode</td>
                        <td class="fw-bold text-end pe-3" style="color:var(--hijau);font-size:.75rem">
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Stok Habis / Hampir Habis --}}
@if($barangStokRendah->count() > 0)
<div class="card-hijau mb-4">
    <div class="card-header-hijau">
        <div class="title"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Barang Stok Habis / Perlu Restock</div>
        <span class="badge-merah">{{ $barangStokRendah->count() }} item</span>
    </div>
    <div class="table-responsive">
        <table class="table tbl-hijau">
            <thead>
                <tr>
                    <th>No</th><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Satuan</th><th>Stok</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangStokRendah as $i => $b)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><code>{{ $b->kode_barang }}</code></td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->kategori->nama }}</td>
                    <td>{{ $b->satuan->nama }}</td>
                    <td class="fw-bold" style="color:{{ $b->stok == 0 ? '#dc3545' : '#e65100' }}">{{ $b->stok }}</td>
                    <td>
                        @if($b->stok == 0)
                            <span class="badge-merah">Habis</span>
                        @else
                            <span class="badge-kuning">Hampir Habis</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Transaksi Terakhir --}}
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-box-arrow-in-down-right"></i> Barang Masuk Terakhir</div>
                <a href="{{ route('transaksi-masuk.index') }}" class="btn-hijau-outline btn" style="padding:4px 10px;font-size:.78rem">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table tbl-hijau">
                    <thead><tr><th>Tanggal</th><th>Barang</th><th>Jml</th></tr></thead>
                    <tbody>
                        @forelse($txMasukTerakhir as $tx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ Str::limit($tx->barang->nama_barang, 30) }}</td>
                            <td><span class="badge-hijau">+{{ $tx->jumlah }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-box-arrow-up-right"></i> Barang Keluar Terakhir</div>
                <a href="{{ route('transaksi-keluar.index') }}" class="btn-hijau-outline btn" style="padding:4px 10px;font-size:.78rem">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table tbl-hijau">
                    <thead><tr><th>Tanggal</th><th>Barang</th><th>Jml</th></tr></thead>
                    <tbody>
                        @forelse($txKeluarTerakhir as $tx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ Str::limit($tx->barang->nama_barang, 30) }}</td>
                            <td><span class="badge-merah">-{{ $tx->jumlah }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.app')
@php $pageTitle = 'Detail Barang'; $pageIcon = 'archive-fill'; @endphp

@section('content')
<div class="row g-3">
    <div class="col-lg-5">
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-info-circle-fill"></i> Info Barang</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-fill me-1"></i> Edit
                    </a>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body-hijau">
                <table class="table table-borderless mb-0" style="font-size:.88rem">
                    <tr>
                        <td class="text-muted" style="width:130px">Kode Barang</td>
                        <td><code class="fw-bold">{{ $barang->kode_barang }}</code></td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="text-muted">Nama Barang</td>
                        <td class="fw-bold">{{ $barang->nama_barang }}</td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="text-muted">Kategori</td>
                        <td><span class="badge-hijau">{{ $barang->kategori->kode }}</span> {{ $barang->kategori->nama }}</td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="text-muted">Satuan</td>
                        <td>{{ $barang->satuan->nama }}</td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="text-muted">Stok Saat Ini</td>
                        <td>
                            @if($barang->stok == 0)
                                <span class="badge-merah fs-6">0 {{ $barang->satuan->nama }}</span>
                            @elseif($barang->stok <= 5)
                                <span class="badge-kuning fs-6">{{ $barang->stok }} {{ $barang->satuan->nama }}</span>
                            @else
                                <span class="badge-hijau fs-6">{{ $barang->stok }} {{ $barang->satuan->nama }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="text-muted">Harga Satuan</td>
                        <td class="fw-bold">Rp {{ number_format($barang->harga_satuan,0,',','.') }}</td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="text-muted">Total Nilai</td>
                        <td class="fw-bold" style="color:var(--hijau)">
                            Rp {{ number_format($barang->stok * $barang->harga_satuan,0,',','.') }}
                        </td>
                    </tr>
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="text-muted">Status</td>
                        <td>
                            @if($barang->aktif)
                                <span class="badge-hijau">Aktif</span>
                            @else
                                <span class="badge-merah">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @if($barang->keterangan)
                    <tr style="border-top:1px solid #eef6f1">
                        <td class="text-muted">Keterangan</td>
                        <td>{{ $barang->keterangan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        {{-- Riwayat Masuk --}}
        <div class="card-hijau mb-3">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-box-arrow-in-down-right"></i> Riwayat Barang Masuk</div>
            </div>
            <div class="table-responsive">
                <table class="table tbl-hijau">
                    <thead><tr><th>Tanggal</th><th>Jumlah</th><th>Harga</th><th>Total</th><th>No. Dok</th></tr></thead>
                    <tbody>
                        @forelse($riwayatMasuk as $tx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                            <td><span class="badge-hijau">+{{ $tx->jumlah }}</span></td>
                            <td>Rp {{ number_format($tx->harga_satuan,0,',','.') }}</td>
                            <td>Rp {{ number_format($tx->jumlah * $tx->harga_satuan,0,',','.') }}</td>
                            <td style="font-size:.8rem">{{ $tx->no_dokumen ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat masuk</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Riwayat Keluar --}}
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-box-arrow-up-right"></i> Riwayat Barang Keluar</div>
            </div>
            <div class="table-responsive">
                <table class="table tbl-hijau">
                    <thead><tr><th>Tanggal</th><th>Jumlah</th><th>Harga</th><th>Total</th><th>Penerima</th></tr></thead>
                    <tbody>
                        @forelse($riwayatKeluar as $tx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($tx->tanggal)->format('d/m/Y') }}</td>
                            <td><span class="badge-merah">-{{ $tx->jumlah }}</span></td>
                            <td>Rp {{ number_format($tx->harga_satuan,0,',','.') }}</td>
                            <td>Rp {{ number_format($tx->jumlah * $tx->harga_satuan,0,',','.') }}</td>
                            <td style="font-size:.8rem">{{ $tx->penerima ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat keluar</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

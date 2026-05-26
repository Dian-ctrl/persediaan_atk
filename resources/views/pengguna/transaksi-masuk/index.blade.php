@extends('layouts.pengguna')
@php $pageTitle = 'Barang Masuk'; $pageIcon = 'box-arrow-in-down-right'; @endphp

@section('content')

<div class="card-biru mb-3">
    <div class="card-body-biru">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Cari Barang</label>
                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Nama barang..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Dari</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Sampai</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-biru btn-sm me-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('pengguna.transaksi-masuk.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card-biru">
    <div class="card-header-biru">
        <div class="title"><i class="bi bi-box-arrow-in-down-right"></i> Barang Masuk ({{ $data->total() }})</div>
        <a href="{{ route('pengguna.transaksi-masuk.create') }}" class="btn btn-biru">
            <i class="bi bi-plus-circle me-1"></i> Input Barang Masuk
        </a>
    </div>
    <div class="table-responsive">
        <table class="table tbl-biru">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Total</th>
                    <th>No. Dokumen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td>{{ $data->firstItem() + $loop->index }}</td>
                    <td><code style="font-size:.78rem">{{ $d->no_transaksi }}</code></td>
                    <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</td>
                    <td style="font-size:.87rem">{{ $d->barang->nama_barang }}</td>
                    <td class="text-center"><span class="badge-hijau">+{{ $d->jumlah }} {{ $d->barang->satuan->nama }}</span></td>
                    <td class="text-end" style="font-size:.84rem">Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                    <td class="text-end fw-bold" style="font-size:.84rem;color:var(--hijau)">
                        Rp {{ number_format($d->jumlah * $d->harga_satuan,0,',','.') }}
                    </td>
                    <td style="font-size:.8rem;color:#888">{{ $d->no_dokumen ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data barang masuk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $data->withQueryString()->links() }}</div>
</div>
@endsection

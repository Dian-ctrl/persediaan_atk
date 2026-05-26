@extends('layouts.app')
@php $pageTitle = 'Barang Keluar'; $pageIcon = 'box-arrow-up-right'; @endphp

@section('content')

<div class="card-hijau mb-3">
    <div class="card-body-hijau">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Cari Barang</label>
                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Nama barang..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Sampai</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:.8rem;font-weight:600">Periode</label>
                <input type="text" name="periode" class="form-control form-control-sm" placeholder="Contoh: Januari 2026" value="{{ request('periode') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-hijau btn-sm me-1"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card-hijau">
    <div class="card-header-hijau">
        <div class="title"><i class="bi bi-box-arrow-up-right"></i> Data Barang Keluar ({{ $data->total() }})</div>
        <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-hijau">
            <i class="bi bi-plus-circle me-1"></i> Input Barang Keluar
        </a>
    </div>
    <div class="table-responsive">
        <table class="table tbl-hijau">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Periode</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-end">Total</th>
                    <th>Penerima</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td>{{ $data->firstItem() + $loop->index }}</td>
                    <td><code style="font-size:.78rem">{{ $d->no_transaksi }}</code></td>
                    <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</td>
                    <td><span class="badge-hijau" style="font-size:.7rem">{{ $d->periode }}</span></td>
                    <td style="font-size:.87rem">{{ $d->barang->nama_barang }}</td>
                    <td class="text-center"><span class="badge-merah">-{{ $d->jumlah }} {{ $d->barang->satuan->nama }}</span></td>
                    <td class="text-end" style="font-size:.84rem">Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                    <td class="text-end fw-bold" style="font-size:.84rem;color:#dc3545">
                        Rp {{ number_format($d->jumlah * $d->harga_satuan,0,',','.') }}
                    </td>
                    <td style="font-size:.8rem">{{ $d->penerima ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('transaksi-keluar.edit', $d->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <form action="{{ route('transaksi-keluar.destroy', $d->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus transaksi ini? Stok barang akan ditambahkan kembali.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-4 text-muted">Tidak ada data transaksi keluar</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $data->withQueryString()->links() }}</div>
</div>
@endsection

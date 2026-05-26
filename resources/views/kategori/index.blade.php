@extends('layouts.app')
@php $pageTitle = 'Kategori Barang'; $pageIcon = 'collection-fill'; @endphp

@section('content')
<div class="card-hijau">
    <div class="card-header-hijau">
        <div class="title"><i class="bi bi-collection-fill"></i> Data Kategori Barang</div>
        <a href="{{ route('kategori.create') }}" class="btn btn-hijau">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>
    <div class="table-responsive">
        <table class="table tbl-hijau">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th style="width:120px">Kode</th>
                    <th>Nama Kategori</th>
                    <th>Keterangan</th>
                    <th style="width:100px">Jml Barang</th>
                    <th style="width:140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="badge-hijau">{{ $d->kode }}</span></td>
                    <td class="fw-600">{{ $d->nama }}</td>
                    <td class="text-muted" style="font-size:.83rem">{{ $d->keterangan ?? '-' }}</td>
                    <td><span class="badge-hijau">{{ $d->barang_count }}</span></td>
                    <td>
                        <a href="{{ route('kategori.edit', $d->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <form action="{{ route('kategori.destroy', $d->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus kategori {{ $d->nama }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

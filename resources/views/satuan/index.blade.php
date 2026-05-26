@extends('layouts.app')
@php $pageTitle = 'Satuan'; $pageIcon = 'tags-fill'; @endphp

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-tags-fill"></i> Data Satuan</div>
                <a href="{{ route('satuan.create') }}" class="btn btn-hijau">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Satuan
                </a>
            </div>
            <div class="table-responsive">
                <table class="table tbl-hijau">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Nama Satuan</th>
                            <th style="width:100px">Jml Barang</th>
                            <th style="width:130px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-600">{{ $d->nama }}</td>
                            <td><span class="badge-hijau">{{ $d->barang_count }}</span></td>
                            <td>
                                <a href="{{ route('satuan.edit', $d->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('satuan.destroy', $d->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus satuan {{ $d->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data satuan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Form inline di kanan --}}
    <div class="col-lg-4">
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-plus-circle"></i>
                    {{ isset($satuan) ? 'Edit Satuan' : 'Tambah Satuan' }}
                </div>
            </div>
            <div class="card-body-hijau">
                <form action="{{ isset($satuan) ? route('satuan.update', $satuan->id) : route('satuan.store') }}" method="POST">
                    @csrf
                    @if(isset($satuan)) @method('PUT') @endif
                    <div class="mb-3">
                        <label class="form-label">Nama Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $satuan->nama ?? '') }}"
                               placeholder="Contoh: Buah, Rim, Dos...">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-hijau">
                            <i class="bi bi-save me-1"></i> {{ isset($satuan) ? 'Update' : 'Simpan' }}
                        </button>
                        @isset($satuan)
                            <a href="{{ route('satuan.index') }}" class="btn btn-secondary">Batal</a>
                        @endisset
                    </div>
                </form>
            </div>
        </div>

        {{-- Info satuan yang ada --}}
        <div class="card-hijau mt-3">
            <div class="card-header-hijau">
                <div class="title"><i class="bi bi-info-circle"></i> Satuan Tersedia</div>
            </div>
            <div class="card-body-hijau">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($data as $d)
                        <span class="badge-hijau">{{ $d->nama }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

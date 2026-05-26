@extends('layouts.app')
@php
    $isEdit = isset($kategori);
    $pageTitle = $isEdit ? 'Edit Kategori' : 'Tambah Kategori';
    $pageIcon  = 'collection-fill';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title">
                    <i class="bi bi-collection-fill"></i>
                    {{ $isEdit ? 'Edit Kategori: '.$kategori->nama : 'Tambah Kategori Baru' }}
                </div>
                <a href="{{ route('kategori.index') }}" class="btn btn-hijau-outline btn">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-hijau">
                <form action="{{ $isEdit ? route('kategori.update', $kategori->id) : route('kategori.store') }}" method="POST">
                    @csrf
                    @if($isEdit) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                               value="{{ old('kode', $kategori->kode ?? '') }}"
                               placeholder="Contoh: ATK/01" maxlength="10">
                        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $kategori->nama ?? '') }}"
                               placeholder="Contoh: Alat Tulis Kantor">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"
                                  placeholder="Deskripsi singkat kategori ini...">{{ old('keterangan', $kategori->keterangan ?? '') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-hijau">
                            <i class="bi bi-save me-1"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

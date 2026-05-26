@extends('layouts.app')
@php
    $isEdit    = isset($barang);
    $pageTitle = $isEdit ? 'Edit Barang' : 'Tambah Barang';
    $pageIcon  = 'archive-fill';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title">
                    <i class="bi bi-archive-fill"></i>
                    {{ $isEdit ? 'Edit: '.$barang->nama_barang : 'Tambah Barang Baru' }}
                </div>
                <a href="{{ route('barang.index') }}" class="btn btn-hijau-outline btn">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-hijau">
                <form action="{{ $isEdit ? route('barang.update', $barang->id) : route('barang.store') }}" method="POST">
                    @csrf
                    @if($isEdit) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" name="kode_barang"
                                   class="form-control @error('kode_barang') is-invalid @enderror"
                                   value="{{ old('kode_barang', $barang->kode_barang ?? '') }}"
                                   placeholder="Contoh: AMP001" maxlength="20">
                            @error('kode_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang"
                                   class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang', $barang->nama_barang ?? '') }}"
                                   placeholder="Masukkan nama barang lengkap">
                            @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}"
                                        {{ old('kategori_id', $barang->kategori_id ?? '') == $k->id ? 'selected' : '' }}>
                                        {{ $k->kode }} - {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror">
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuans as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('satuan_id', $barang->satuan_id ?? '') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('satuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stok" min="0"
                                   class="form-control @error('stok') is-invalid @enderror"
                                   value="{{ old('stok', $barang->stok ?? 0) }}">
                            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--hijau-bg);border-color:#ced4da">Rp</span>
                                <input type="number" name="harga_satuan" min="0"
                                       class="form-control @error('harga_satuan') is-invalid @enderror"
                                       value="{{ old('harga_satuan', $barang->harga_satuan ?? 0) }}"
                                       placeholder="0">
                                @error('harga_satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"
                                      placeholder="Keterangan tambahan (opsional)...">{{ old('keterangan', $barang->keterangan ?? '') }}</textarea>
                        </div>

                        @if($isEdit)
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="aktif" value="1" id="aktif"
                                       class="form-check-input"
                                       {{ old('aktif', $barang->aktif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="aktif">Barang masih aktif digunakan</label>
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr style="border-color:var(--hijau-border)">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-hijau">
                            <i class="bi bi-save me-1"></i> {{ $isEdit ? 'Update Barang' : 'Simpan Barang' }}
                        </button>
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

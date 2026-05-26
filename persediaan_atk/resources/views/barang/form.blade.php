@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <div>
        <h1>{{ isset($barang) ? 'Edit Barang' : 'Tambah Barang' }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <a href="{{ route('barang.index') }}">Data Barang</a>
            <span class="sep">/</span>
            <span>{{ isset($barang) ? 'Edit' : 'Tambah' }}</span>
        </div>
    </div>
</div>

<div style="max-width:680px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                {{ isset($barang) ? 'Edit Barang' : 'Form Tambah Barang' }}
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ isset($barang) ? route('barang.update', $barang) : route('barang.store') }}">
                @csrf
                @if(isset($barang)) @method('PUT') @endif

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label">Kode Barang <span class="required">*</span></label>
                        <input type="text" name="kode" class="form-control" placeholder="Otomatis / manual" value="{{ old('kode', $barang->kode ?? '') }}" required>
                        @error('kode')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Barang <span class="required">*</span></label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Nama barang..." value="{{ old('nama_barang', $barang->nama_barang ?? '') }}" required>
                        @error('nama_barang')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id', $barang->kategori_id ?? '') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Satuan <span class="required">*</span></label>
                        <select name="satuan_id" class="form-control" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($satuans as $sat)
                            <option value="{{ $sat->id }}" {{ old('satuan_id', $barang->satuan_id ?? '') == $sat->id ? 'selected' : '' }}>
                                {{ $sat->nama_satuan }}
                            </option>
                            @endforeach
                        </select>
                        @error('satuan_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label">Harga Satuan <span class="required">*</span></label>
                        <input type="number" name="harga_satuan" class="form-control" placeholder="0" value="{{ old('harga_satuan', $barang->harga_satuan ?? '') }}" min="0" required>
                        @error('harga_satuan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control" placeholder="0" value="{{ old('stok', $barang->stok ?? 0) }}" min="0">
                        <div class="form-hint">Stok saat pertama kali diinput</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ old('keterangan', $barang->keterangan ?? '') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end">
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        {{ isset($barang) ? 'Update Barang' : 'Simpan Barang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

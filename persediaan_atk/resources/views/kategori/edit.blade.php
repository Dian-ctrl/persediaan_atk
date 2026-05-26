@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
    </div>
    <div>
        <h1>Edit Kategori</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <a href="{{ route('kategori.index') }}">Kategori Barang</a>
            <span class="sep">/</span>
            <span>Edit</span>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

    <!-- TABLE -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Data Kategori Barang
            </div>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE</th>
                        <th>NAMA KATEGORI</th>
                        <th>KETERANGAN</th>
                        <th>JML BARANG</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $i => $kat)
                    <tr class="{{ $kat->id == $kategori->id ? 'highlighted' : '' }}" style="{{ $kat->id == $kategori->id ? 'background:var(--primary-pale)' : '' }}">
                        <td>{{ $kategoris->firstItem() + $i }}</td>
                        <td><span class="badge badge-green">{{ $kat->kode }}</span></td>
                        <td style="font-weight:600">{{ $kat->nama_kategori }}</td>
                        <td style="color:var(--text-light)">{{ $kat->keterangan ?? '-' }}</td>
                        <td><span class="stok-num ok">{{ $kat->barang_count }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('kategori.edit', $kat) }}" class="btn btn-warning btn-icon btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('kategori.destroy', $kat) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-icon btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada data kategori</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- FORM EDIT -->
    <div class="card" style="position:sticky;top:80px">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Kategori
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('kategori.update', $kategori) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Kode Kategori <span class="required">*</span></label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode', $kategori->kode) }}" required>
                    @error('kode')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Kategori <span class="required">*</span></label>
                    <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                    @error('nama_kategori')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                </div>
                <div style="display:flex;gap:8px">
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center">Batal</a>
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

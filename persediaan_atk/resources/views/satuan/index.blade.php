@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
    </div>
    <div>
        <h1>Satuan</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <span>Satuan</span>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Data Satuan
            </div>
            <a href="{{ route('satuan.create') }}" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Satuan
            </a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA SATUAN</th>
                        <th>JML BARANG</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($satuans as $i => $sat)
                    <tr>
                        <td>{{ $satuans->firstItem() + $i }}</td>
                        <td style="font-weight:600">{{ $sat->nama_satuan }}</td>
                        <td><span class="stok-num ok">{{ $sat->barang_count }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('satuan.edit', $sat) }}" class="btn btn-warning btn-icon btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('satuan.destroy', $sat) }}" onsubmit="return confirm('Hapus satuan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-icon btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted)">Belum ada data satuan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SIDEBAR FORM + CHIPS -->
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card" style="position:sticky;top:80px">
            <div class="card-header">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Satuan
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('satuan.store') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Satuan <span class="required">*</span></label>
                        <input type="text" name="nama_satuan" class="form-control" placeholder="Contoh: Buah, Rim, Dos..." value="{{ old('nama_satuan') }}" required>
                        @error('nama_satuan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Satuan Tersedia
                </div>
            </div>
            <div class="card-body">
                <div style="display:flex;flex-wrap:wrap;gap:8px">
                    @foreach($satuans as $sat)
                    <span class="badge badge-green">{{ $sat->nama_satuan }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

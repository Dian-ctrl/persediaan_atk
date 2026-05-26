@extends('layouts.app')
@section('content')

<div class="page-header-bar">
    <div class="page-header-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
    </div>
    <div>
        <h1>{{ isset($transaksi) ? 'Edit Barang Keluar' : 'Input Barang Keluar' }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="sep">/</span>
            <a href="{{ route('barang-keluar.index') }}">Barang Keluar</a>
            <span class="sep">/</span>
            <span>{{ isset($transaksi) ? 'Edit' : 'Input' }}</span>
        </div>
    </div>
</div>

<div style="max-width:680px">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Form Barang Keluar
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ isset($transaksi) ? route('barang-keluar.update', $transaksi) : route('barang-keluar.store') }}">
                @csrf
                @if(isset($transaksi)) @method('PUT') @endif

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label">No. Transaksi</label>
                        <input type="text" class="form-control" value="{{ $noTransaksi }}" readonly style="background:var(--bg)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', isset($transaksi) ? $transaksi->tanggal : date('Y-m-d')) }}" required>
                        @error('tanggal')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Periode <span class="required">*</span></label>
                    <input type="text" name="periode" class="form-control" placeholder="Contoh: April 2026" value="{{ old('periode', $transaksi->periode ?? \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM YYYY')) }}" required>
                    @error('periode')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Barang <span class="required">*</span></label>
                    <select name="barang_id" id="barangSelect" class="form-control" required onchange="updateInfo()">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $b)
                        <option value="{{ $b->id }}"
                            data-harga="{{ $b->harga_satuan }}"
                            data-stok="{{ $b->stok }}"
                            data-satuan="{{ $b->satuan->nama_satuan ?? '' }}"
                            {{ old('barang_id', $transaksi->barang_id ?? '') == $b->id ? 'selected' : '' }}>
                            {{ $b->kode }} - {{ $b->nama_barang }} (Stok: {{ $b->stok }})
                        </option>
                        @endforeach
                    </select>
                    @error('barang_id')<div class="form-error">{{ $message }}</div>@enderror
                    <div id="stokInfo" class="form-hint"></div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
                    <div class="form-group">
                        <label class="form-label">Jumlah <span class="required">*</span></label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="0" value="{{ old('jumlah', $transaksi->jumlah ?? '') }}" min="1" required onchange="hitungTotal()">
                        @error('jumlah')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Satuan <span class="required">*</span></label>
                        <input type="number" name="harga_satuan" id="hargaSatuan" class="form-control" placeholder="0" value="{{ old('harga_satuan', $transaksi->harga_satuan ?? '') }}" min="0" required onchange="hitungTotal()">
                        @error('harga_satuan')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total</label>
                        <input type="text" id="totalDisplay" class="form-control" readonly style="background:#FEF2F2;font-weight:700;color:#C0392B" placeholder="Rp 0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Penerima</label>
                    <input type="text" name="penerima" class="form-control" placeholder="Nama penerima / bagian..." value="{{ old('penerima', $transaksi->penerima ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ old('keterangan', $transaksi->keterangan ?? '') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end">
                    <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateInfo() {
    const sel = document.getElementById('barangSelect');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset.harga) {
        document.getElementById('hargaSatuan').value = opt.dataset.harga;
        const stok = opt.dataset.stok;
        document.getElementById('stokInfo').innerHTML =
            `<span style="color:${stok > 0 ? 'var(--primary)' : 'var(--danger)'}">Stok tersedia: <strong>${stok}</strong> ${opt.dataset.satuan}</span>`;
        hitungTotal();
    }
}
function hitungTotal() {
    const jml = parseInt(document.getElementById('jumlah').value) || 0;
    const harga = parseInt(document.getElementById('hargaSatuan').value) || 0;
    document.getElementById('totalDisplay').value = 'Rp ' + (jml * harga).toLocaleString('id-ID');
}
hitungTotal();
</script>
@endpush
@endsection

@extends('layouts.app')
@php
    $isEdit    = isset($transaksi);
    $pageTitle = $isEdit ? 'Edit Barang Keluar' : 'Input Barang Keluar';
    $pageIcon  = 'box-arrow-up-right';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-hijau">
            <div class="card-header-hijau">
                <div class="title">
                    <i class="bi bi-box-arrow-up-right"></i>
                    {{ $isEdit ? 'Edit Transaksi Keluar' : 'Input Barang Keluar' }}
                </div>
                <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-hijau-outline btn">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-hijau">
                <form action="{{ $isEdit ? route('transaksi-keluar.update', $transaksi->id) : route('transaksi-keluar.store') }}" method="POST">
                    @csrf
                    @if($isEdit) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">No. Transaksi <span class="text-danger">*</span></label>
                            <input type="text" name="no_transaksi"
                                   class="form-control @error('no_transaksi') is-invalid @enderror"
                                   value="{{ old('no_transaksi', $transaksi->no_transaksi ?? $noTransaksi) }}">
                            @error('no_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', $transaksi->tanggal ?? date('Y-m-d')) }}">
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Periode <span class="text-danger">*</span></label>
                            <input type="text" name="periode"
                                   class="form-control @error('periode') is-invalid @enderror"
                                   value="{{ old('periode', $transaksi->periode ?? \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y')) }}">
                            @error('periode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Barang <span class="text-danger">*</span></label>
                            <select name="barang_id" id="barang_id"
                                    class="form-select @error('barang_id') is-invalid @enderror"
                                    onchange="isiHarga(this)">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}"
                                        data-harga="{{ $b->harga_satuan }}"
                                        data-satuan="{{ $b->satuan->nama }}"
                                        data-stok="{{ $b->stok }}"
                                        {{ old('barang_id', $transaksi->barang_id ?? '') == $b->id ? 'selected' : '' }}>
                                        [{{ $b->kode_barang }}] {{ $b->nama_barang }} — Stok: {{ $b->stok }} {{ $b->satuan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="stok-info" class="mt-1" style="font-size:.82rem;color:var(--hijau)"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="jumlah" id="jumlah" min="1"
                                       class="form-control @error('jumlah') is-invalid @enderror"
                                       value="{{ old('jumlah', $transaksi->jumlah ?? 1) }}"
                                       onchange="hitungTotal()">
                                <span class="input-group-text" id="satuan-label" style="background:var(--hijau-bg)">unit</span>
                            </div>
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Harga Satuan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--hijau-bg)">Rp</span>
                                <input type="number" name="harga_satuan" id="harga_satuan" min="0"
                                       class="form-control"
                                       value="{{ old('harga_satuan', $transaksi->harga_satuan ?? 0) }}"
                                       onchange="hitungTotal()">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Total Nilai</label>
                            <div class="form-control" id="total_display"
                                 style="background:var(--hijau-bg);font-weight:700;color:var(--hijau)">Rp 0</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Penerima / Bagian</label>
                            <input type="text" name="penerima"
                                   class="form-control"
                                   value="{{ old('penerima', $transaksi->penerima ?? '') }}"
                                   placeholder="Nama divisi/bagian penerima">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No. Dokumen (Bon Permintaan)</label>
                            <input type="text" name="no_dokumen"
                                   class="form-control"
                                   value="{{ old('no_dokumen', $transaksi->no_dokumen ?? '') }}"
                                   placeholder="Nomor bon permintaan barang">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"
                                      placeholder="Keterangan tambahan...">{{ old('keterangan', $transaksi->keterangan ?? '') }}</textarea>
                        </div>
                    </div>

                    <hr style="border-color:var(--hijau-border)">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-hijau">
                            <i class="bi bi-save me-1"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function isiHarga(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('harga_satuan').value = opt.dataset.harga || 0;
    document.getElementById('satuan-label').textContent = opt.dataset.satuan || 'unit';
    const stok = opt.dataset.stok || 0;
    document.getElementById('stok-info').textContent =
        stok > 0 ? `Stok tersedia: ${stok} ${opt.dataset.satuan}` : '⚠ Stok habis!';
    document.getElementById('stok-info').style.color = stok > 0 ? 'var(--hijau)' : '#dc3545';
    hitungTotal();
}
function hitungTotal() {
    const jml   = parseFloat(document.getElementById('jumlah').value) || 0;
    const harga = parseFloat(document.getElementById('harga_satuan').value) || 0;
    document.getElementById('total_display').textContent =
        'Rp ' + (jml * harga).toLocaleString('id-ID');
}
window.addEventListener('load', function() {
    hitungTotal();
    const sel = document.getElementById('barang_id');
    if (sel.value) isiHarga(sel);
});
</script>
@endpush

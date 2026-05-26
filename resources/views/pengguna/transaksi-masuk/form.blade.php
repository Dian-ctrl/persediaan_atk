@extends('layouts.pengguna')
@php $pageTitle = 'Input Barang Masuk'; $pageIcon = 'box-arrow-in-down-right'; @endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="info-banner">
            <i class="bi bi-info-circle-fill" style="font-size:1.1rem;color:var(--biru)"></i>
            <div>Setelah disimpan, <strong>stok barang otomatis bertambah</strong> sesuai jumlah yang dimasukkan.</div>
        </div>

        <div class="card-biru">
            <div class="card-header-biru">
                <div class="title"><i class="bi bi-box-arrow-in-down-right"></i> Form Input Barang Masuk</div>
                <a href="{{ route('pengguna.transaksi-masuk.index') }}" class="btn btn-biru-outline btn">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-biru">
                <form action="{{ route('pengguna.transaksi-masuk.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">No. Transaksi <span class="text-danger">*</span></label>
                            <input type="text" name="no_transaksi"
                                   class="form-control @error('no_transaksi') is-invalid @enderror"
                                   value="{{ old('no_transaksi', $noTransaksi) }}">
                            @error('no_transaksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', date('Y-m-d')) }}">
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Periode <span class="text-danger">*</span></label>
                            <input type="text" name="periode"
                                   class="form-control @error('periode') is-invalid @enderror"
                                   value="{{ old('periode', \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y')) }}">
                            @error('periode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                            <select name="barang_id" id="barang_id"
                                    class="form-select @error('barang_id') is-invalid @enderror"
                                    onchange="isiHarga(this)">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}"
                                        data-harga="{{ $b->harga_satuan }}"
                                        data-satuan="{{ $b->satuan->nama }}"
                                        data-stok="{{ $b->stok }}"
                                        {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                        [{{ $b->kode_barang }}] {{ $b->nama_barang }} — Stok: {{ $b->stok }} {{ $b->satuan->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="stok-info" class="mt-1" style="font-size:.82rem"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jumlah Masuk <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="jumlah" id="jumlah" min="1"
                                       class="form-control @error('jumlah') is-invalid @enderror"
                                       value="{{ old('jumlah', 1) }}" onchange="hitungTotal()">
                                <span class="input-group-text" id="satuan-label" style="background:var(--biru-bg)">unit</span>
                            </div>
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Harga Satuan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--biru-bg)">Rp</span>
                                <input type="number" name="harga_satuan" id="harga_satuan" min="0"
                                       class="form-control" value="{{ old('harga_satuan', 0) }}"
                                       onchange="hitungTotal()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Nilai</label>
                            <div class="form-control fw-bold" id="total_display"
                                 style="background:var(--biru-bg);color:var(--biru)">Rp 0</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sumber / Asal Barang</label>
                            <input type="text" name="sumber" class="form-control"
                                   value="{{ old('sumber') }}" placeholder="Pengadaan, Hibah, dll...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Dokumen</label>
                            <input type="text" name="no_dokumen" class="form-control"
                                   value="{{ old('no_dokumen') }}" placeholder="Faktur / Surat Jalan">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"
                                      placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    {{-- Preview stok setelah --}}
                    <div id="preview-box" class="mt-3 p-3"
                         style="background:#f0fdf4;border:1px solid #c6e8d5;border-radius:8px;display:none;font-size:.83rem">
                        <i class="bi bi-graph-up-arrow me-1" style="color:var(--hijau)"></i>
                        Preview stok setelah disimpan: <strong id="stok-setelah" style="color:var(--hijau)">—</strong>
                    </div>

                    <hr style="border-color:var(--biru-border)">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-biru">
                            <i class="bi bi-save me-1"></i> Simpan Barang Masuk
                        </button>
                        <a href="{{ route('pengguna.transaksi-masuk.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let stokSkrg = 0;
function isiHarga(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('harga_satuan').value = opt.dataset.harga || 0;
    document.getElementById('satuan-label').textContent = opt.dataset.satuan || 'unit';
    stokSkrg = parseInt(opt.dataset.stok) || 0;
    const info = document.getElementById('stok-info');
    info.innerHTML = `<i class="bi bi-info-circle me-1"></i>Stok saat ini: <strong>${stokSkrg} ${opt.dataset.satuan}</strong>`;
    info.style.color = '#1e40af';
    hitungTotal();
}
function hitungTotal() {
    const jml   = parseInt(document.getElementById('jumlah').value) || 0;
    const harga = parseFloat(document.getElementById('harga_satuan').value) || 0;
    document.getElementById('total_display').textContent = 'Rp ' + (jml * harga).toLocaleString('id-ID');
    const barangEl = document.getElementById('barang_id');
    const preview  = document.getElementById('preview-box');
    const setelah  = document.getElementById('stok-setelah');
    if (barangEl.value && jml > 0) {
        const opt = barangEl.options[barangEl.selectedIndex];
        setelah.textContent = (stokSkrg + jml) + ' ' + (opt.dataset.satuan || 'unit');
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}
window.addEventListener('load', () => {
    hitungTotal();
    const sel = document.getElementById('barang_id');
    if (sel.value) isiHarga(sel);
});
</script>
@endpush

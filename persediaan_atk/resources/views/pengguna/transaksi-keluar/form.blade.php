@extends('layouts.pengguna')
@php $pageTitle = 'Input Barang Keluar'; $pageIcon = 'box-arrow-up-right'; @endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="info-banner" style="background:linear-gradient(90deg,#fff7ed,#fef9f0);border-color:#fed7aa;color:#7c2d12">
            <i class="bi bi-exclamation-triangle-fill" style="color:#c2410c;font-size:1.1rem"></i>
            <div>Setelah disimpan, <strong>stok barang otomatis berkurang</strong>. Pastikan jumlah sudah benar sebelum menyimpan.</div>
        </div>

        <div class="card-biru">
            <div class="card-header-biru">
                <div class="title"><i class="bi bi-box-arrow-up-right"></i> Form Input Barang Keluar</div>
                <a href="{{ route('pengguna.transaksi-keluar.index') }}" class="btn btn-biru-outline btn">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body-biru">
                <form action="{{ route('pengguna.transaksi-keluar.store') }}" method="POST">
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
                            <label class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
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
                            <label class="form-label">Penerima / Bagian</label>
                            <input type="text" name="penerima" class="form-control"
                                   value="{{ old('penerima') }}" placeholder="Nama divisi / bagian penerima">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Dokumen</label>
                            <input type="text" name="no_dokumen" class="form-control"
                                   value="{{ old('no_dokumen') }}" placeholder="No. bon permintaan barang">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"
                                      placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    {{-- Preview stok setelah --}}
                    <div id="preview-ok" class="mt-3 p-3"
                         style="background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;display:none;font-size:.83rem">
                        <i class="bi bi-graph-down-arrow me-1" style="color:#c2410c"></i>
                        Preview stok setelah disimpan: <strong id="stok-setelah" style="color:#c2410c">—</strong>
                    </div>
                    <div id="preview-warning" class="mt-3 p-3"
                         style="background:#fff0f0;border:1px solid #f5c6cb;border-radius:8px;display:none;font-size:.83rem;color:#721c24">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>⚠ Jumlah melebihi stok tersedia!</strong> Kurangi jumlah sebelum menyimpan.
                    </div>

                    <hr style="border-color:var(--biru-border)">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-biru" id="btn-submit">
                            <i class="bi bi-save me-1"></i> Simpan Barang Keluar
                        </button>
                        <a href="{{ route('pengguna.transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
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
    info.innerHTML = `<i class="bi bi-boxes me-1"></i>Stok tersedia: <strong>${stokSkrg} ${opt.dataset.satuan}</strong>`;
    info.style.color = stokSkrg > 0 ? '#059669' : '#dc3545';
    hitungTotal();
}
function hitungTotal() {
    const jml   = parseInt(document.getElementById('jumlah').value) || 0;
    const harga = parseFloat(document.getElementById('harga_satuan').value) || 0;
    document.getElementById('total_display').textContent = 'Rp ' + (jml * harga).toLocaleString('id-ID');

    const barangEl = document.getElementById('barang_id');
    const preOk    = document.getElementById('preview-ok');
    const preWarn  = document.getElementById('preview-warning');
    const setelah  = document.getElementById('stok-setelah');
    const btn      = document.getElementById('btn-submit');

    if (barangEl.value && jml > 0) {
        const opt  = barangEl.options[barangEl.selectedIndex];
        const sisa = stokSkrg - jml;
        setelah.textContent = sisa + ' ' + (opt.dataset.satuan || 'unit');
        if (sisa < 0) {
            preOk.style.display   = 'none';
            preWarn.style.display = 'block';
            btn.disabled = true;
        } else {
            preOk.style.display   = 'block';
            preWarn.style.display = 'none';
            btn.disabled = false;
        }
    } else {
        preOk.style.display   = 'none';
        preWarn.style.display = 'none';
        btn.disabled = false;
    }
}
window.addEventListener('load', () => {
    hitungTotal();
    const sel = document.getElementById('barang_id');
    if (sel.value) isiHarga(sel);
});
</script>
@endpush

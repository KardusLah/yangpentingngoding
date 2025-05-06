{{-- filepath: resources/views/fe/reservasi/index.blade.php --}}
@extends('fe.master')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div class="container py-5">
    <h2 class="mb-4">Reservasi Paket Wisata</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('reservasi.store') }}" method="POST" enctype="multipart/form-data" id="formBooking">
        @csrf
        <div class="row">
            <div class="col-md-7">
                <div class="mb-3">
                    <label>Paket Wisata</label>
                    <select name="id_paket" id="id_paket" class="form-control" required>
                        <option value="">Pilih Paket Wisata</option>
                        @foreach($pakets as $paket)
                            <option value="{{ $paket->id }}"
                                data-harga="{{ $paket->harga_per_pack }}"
                                data-durasi="{{ $paket->durasi }}"
                                data-diskon='@json(($diskon[$paket->id] ?? collect())->map(function($d){
                                    return [
                                        'persen' => $d->persen,
                                        'mulai' => $d->tanggal_mulai,
                                        'akhir' => $d->tanggal_akhir
                                    ];
                                })->values())'
                                {{ (old('id_paket') == $paket->id || (isset($paketTerpilih) && $paketTerpilih == $paket->id)) ? 'selected' : '' }}
                            >{{ $paket->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Jumlah Peserta</label>
                    <input type="number" name="jumlah_peserta" id="jumlah_peserta" class="form-control" min="1" required>
                </div>
                <div class="mb-3">
                    <label>Tanggal Mulai</label>
                    <input type="text" name="tgl_mulai" id="tgl_mulai" class="form-control datepicker" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label>Tanggal Akhir</label>
                    <input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control datepicker" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                        <option value="">Pilih Metode</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="ewallet">E-Wallet</option>
                        <option value="kartu">Kartu Kredit</option>
                    </select>
                </div>
                <div class="mb-3" id="bukti_transfer_group" style="display:none;">
                    <label>Bukti Transfer</label>
                    <input type="file" name="file_bukti_tf" class="form-control">
                    <small class="text-muted">Unggah bukti transfer jika memilih Transfer Bank.</small>
                </div>
                <button class="btn btn-primary">Pesan Sekarang</button>
            </div>
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Ringkasan Pemesanan</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-2"><strong>Paket:</strong> <span id="summary_paket">-</span></div>
                        <div class="mb-2"><strong>Jumlah Peserta:</strong> <span id="summary_peserta">-</span></div>
                        <div class="mb-2"><strong>Tanggal:</strong> <span id="summary_tanggal">-</span></div>
                        <div class="mb-2"><strong>Lama:</strong> <span id="summary_lama">-</span> hari</div>
                        <div class="mb-2"><strong>Harga per Hari:</strong> <span id="summary_harga">-</span></div>
                        <div class="mb-2 text-success" id="summary_diskon"></div>
                        <hr>
                        <div class="mb-2"><strong>Total Bayar:</strong> <span id="summary_total">-</span></div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="total_bayar" id="total_bayar">
    </form>
</div>
@endsection


@section('scripts')
<script src="{{ asset('be/assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
<script>
const tanggalPenuh = @json($tanggalPenuh ?? []);

function getMaxDurasi() {
    const select = document.getElementById('id_paket');
    const opt = select.options[select.selectedIndex];
    return parseInt(opt?.getAttribute('data-durasi') || 1);
}
function getTanggalPenuh() {
    const select = document.getElementById('id_paket');
    const paketId = select.value;
    return tanggalPenuh[paketId] || [];
}
function getNextValidStart(minDate, penuh) {
    let date = new Date(minDate);
    for (let i = 0; i < 365; i++) {
        const tgl = date.toISOString().slice(0,10);
        if (!penuh.includes(tgl)) return tgl;
        date.setDate(date.getDate() + 1);
    }
    return null;
}

let fpMulai, fpAkhir;

function updateFlatpickr() {
    const maxDurasi = getMaxDurasi();
    const penuh = getTanggalPenuh();

    if (fpMulai) fpMulai.destroy();
    if (fpAkhir) fpAkhir.destroy();

    fpMulai = flatpickr("#tgl_mulai", {
        minDate: "today",
        disable: penuh,
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
            if (!dateStr) return;
            if (penuh.includes(dateStr)) {
                const nextValid = getNextValidStart(dateStr, penuh);
                if (nextValid) {
                    fpMulai.setDate(nextValid, true);
                    return;
                }
            }
            const min = dateStr;
            const max = new Date(new Date(dateStr).getTime() + (maxDurasi-1)*24*60*60*1000);
            fpAkhir.set('minDate', min);
            fpAkhir.set('maxDate', max);
            fpAkhir.set('disable', penuh);
            fpAkhir.setDate(min, true);
        }
    });

    fpAkhir = flatpickr("#tgl_akhir", {
        minDate: "today",
        disable: penuh,
        dateFormat: "Y-m-d"
    });
}

document.addEventListener('DOMContentLoaded', function() {
    updateFlatpickr();
});
document.getElementById('id_paket').addEventListener('change', updateFlatpickr);
</script>
@endsection
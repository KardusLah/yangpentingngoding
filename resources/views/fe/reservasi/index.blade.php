{{-- filepath: c:\xampp\htdocs\WISATA\reservasi-online\resources\views\fe\reservasi\index.blade.php --}}
@extends('fe.master')

@section('content')
<div class="hero hero-inner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mx-auto text-center">
                <div class="intro-wrap">
                    <h1 class="mb-0">Reservasi Paket Wisata</h1>
                    <p class="text-white">Pilih paket wisata favorit Anda dan nikmati pengalaman liburan tak terlupakan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="untree_co-section">
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger rounded-20">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm rounded-20 mb-4">
                    <div class="card-body p-4">
                        <h3 class="section-title mb-4">Detail Reservasi</h3>
                        <form action="{{ route('reservasi.store') }}" method="POST" enctype="multipart/form-data" id="formBooking">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Paket Wisata</label>
                                <select name="id_paket" id="id_paket" class="form-control rounded-20" required>
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
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Jumlah Peserta</label>
                                    <input type="number" name="jumlah_peserta" id="jumlah_peserta" class="form-control rounded-20" min="1" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Durasi (hari)</label>
                                    <input type="text" id="display_durasi" class="form-control rounded-20" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Tanggal Mulai</label>
                                    <input type="text" name="tgl_mulai" id="tgl_mulai" class="form-control rounded-20" required>
                                    <div id="pesan_tgl_mulai" class="text-danger small mt-1"></div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Tanggal Akhir</label>
                                    <input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control rounded-20" required>
                                    <div id="pesan_tgl_akhir" class="text-danger small mt-1"></div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="bank_id" id="bank_id" class="form-control rounded-20" required>
                                    <option value="">Pilih Bank</option>
                                    @foreach(\App\Models\Bank::all() as $bank)
                                        <option value="{{ $bank->id }}"
                                            data-norek="{{ $bank->no_rekening }}"
                                            data-atasnama="{{ $bank->atas_nama }}">
                                            {{ $bank->nama_bank }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="info-rekening" class="alert alert-info rounded-20" style="display:none;">
                                <div class="mb-1">
                                    <span class="fw-bold">No. Rekening:</span>
                                    <span id="info-norek"></span>
                                </div>
                                <div>
                                    <span class="fw-bold">Atas Nama:</span>
                                    <span id="info-atasnama"></span>
                                </div>
                            </div>

                            <div class="mb-4" id="bukti_transfer_group">
                                <label class="form-label fw-bold">Bukti Transfer <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" name="file_bukti_tf" class="form-control rounded-20" required accept="image/*,.pdf">
                                    <small class="text-muted">Format: JPG, PNG, atau PDF</small>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-20 px-4">
                                    <i class="fas fa-calendar-check me-2"></i> Pesan Sekarang
                                </button>
                            </div>
                            
                            <input type="hidden" name="total_bayar" id="total_bayar">
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow-sm rounded-20 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white rounded-top-20">
                        <h4 class="mb-0"><i class="fas fa-receipt me-2"></i> Ringkasan Pemesanan</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold text-muted">Paket:</div>
                            <div id="summary_paket" class="text-end">-</div>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold text-muted">Jumlah Peserta:</div>
                            <div id="summary_peserta">-</div>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold text-muted">Tanggal:</div>
                            <div id="summary_tanggal" class="text-end">-</div>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold text-muted">Durasi:</div>
                            <div><span id="summary_lama">-</span> hari</div>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold text-muted">Harga per Pack:</div>
                            <div id="summary_harga">-</div>
                        </div>
                        <div id="summary_diskon_container" class="d-flex justify-content-between mb-3 pb-2 border-bottom text-success" style="display:none;">
                            <div class="fw-bold">Diskon:</div>
                            <div id="summary_diskon"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-4 pt-2">
                            <div class="fw-bold fs-5">Total Bayar:</div>
                            <div id="summary_total" class="fs-5 fw-bold text-primary">-</div>
                        </div>
                        
                        <div class="alert alert-warning mt-4 small rounded-20">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>Harap periksa kembali data pemesanan sebelum melanjutkan pembayaran.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('fe.footer')
@endsection

@push('styles')
<style>
    .flatpickr-day.disabled {
        color: #ccc;
        background: #f8f9fa;
        cursor: not-allowed;
    }
    .flatpickr-day.booked {
        background: #ffebee;
        color: #f44336;
        border-color: #f44336;
    }
    .flatpickr-calendar {
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        border: none;
    }
    .flatpickr-day.selected {
        background: #1A374D;
        border-color: #1A374D;
    }
</style>
@endpush

@push('scripts')
<!-- Load Flatpickr CSS and JS from CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data tanggal penuh dari controller
    const tanggalPenuh = @json($tanggalPenuh ?? []);
    const formBooking = document.getElementById('formBooking');
    const paketSelect = document.getElementById('id_paket');
    const jumlahPeserta = document.getElementById('jumlah_peserta');
    const displayDurasi = document.getElementById('display_durasi');
    const tglMulaiInput = document.getElementById('tgl_mulai');
    const tglAkhirInput = document.getElementById('tgl_akhir');
    const summaryPaket = document.getElementById('summary_paket');
    const summaryPeserta = document.getElementById('summary_peserta');
    const summaryTanggal = document.getElementById('summary_tanggal');
    const summaryLama = document.getElementById('summary_lama');
    const summaryHarga = document.getElementById('summary_harga');
    const summaryDiskon = document.getElementById('summary_diskon');
    const summaryDiskonContainer = document.getElementById('summary_diskon_container');
    const summaryTotal = document.getElementById('summary_total');
    const totalBayarInput = document.getElementById('total_bayar');
    const pesanMulai = document.getElementById('pesan_tgl_mulai');
    const pesanAkhir = document.getElementById('pesan_tgl_akhir');
    const bankSelect = document.getElementById('bank_id');
    const infoRekening = document.getElementById('info-rekening');
    const infoNorek = document.getElementById('info-norek');
    const infoAtasnama = document.getElementById('info-atasnama');

    // Show rekening info below select
    if (bankSelect) {
        bankSelect.addEventListener('change', function() {
            const norek = this.options[this.selectedIndex].getAttribute('data-norek') || '';
            const atasnama = this.options[this.selectedIndex].getAttribute('data-atasnama') || '';
            if (this.value) {
                infoNorek.textContent = norek;
                infoAtasnama.textContent = atasnama;
                infoRekening.style.display = 'block';
            } else {
                infoRekening.style.display = 'none';
                infoNorek.textContent = '';
                infoAtasnama.textContent = '';
            }
        });
    }

    // Helper functions
    function formatTanggal(dateString) {
        if (!dateString) return '-';
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }
    
    function formatRupiah(angka) {
        if (isNaN(angka) || angka === null) return '-';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(angka);
    }
    
    function getTanggalPenuh() {
        const paketId = paketSelect.value;
        return tanggalPenuh[paketId] || [];
    }
    
    function getNextAvailableDate(startDate, penuhDates) {
        let date = new Date(startDate);
        for (let i = 0; i < 365; i++) {
            const ymd = date.toISOString().slice(0,10);
            if (!penuhDates.includes(ymd)) return ymd;
            date.setDate(date.getDate() + 1);
        }
        return null;
    }
    
    function getMaxDurasi() {
        const opt = paketSelect.options[paketSelect.selectedIndex];
        return parseInt(opt?.getAttribute('data-durasi') || 1);
    }

    // Flatpickr setup
    let tglMulaiPicker, tglAkhirPicker;
    function setupFlatpickr() {
        const penuh = getTanggalPenuh();
        if (tglMulaiPicker) tglMulaiPicker.destroy();
        if (tglAkhirPicker) tglAkhirPicker.destroy();

        tglMulaiPicker = flatpickr(tglMulaiInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: penuh,
            locale: "id",
            onChange: function(selectedDates, dateStr) {
                const maxDurasi = getMaxDurasi();
                let min = dateStr;
                let maxDate = new Date(dateStr);
                maxDate.setDate(maxDate.getDate() + maxDurasi - 1);
                let max = maxDate.toISOString().slice(0,10);
                
                tglAkhirPicker.set('minDate', min);
                tglAkhirPicker.set('maxDate', max);
                tglAkhirPicker.set('disable', penuh);
                
                if (!tglAkhirInput.value || tglAkhirInput.value < min || tglAkhirInput.value > max || penuh.includes(tglAkhirInput.value)) {
                    tglAkhirPicker.setDate(min);
                }
                
                validateTanggalPenuh('tgl_mulai', 'pesan_tgl_mulai');
                validateTanggalRange();
                updateRingkasan();
            }
        });
        
        tglAkhirPicker = flatpickr(tglAkhirInput, {
            dateFormat: "Y-m-d",
            minDate: tglMulaiInput.value || "today",
            maxDate: (() => {
                if (tglMulaiInput.value) {
                    let maxDate = new Date(tglMulaiInput.value);
                    maxDate.setDate(maxDate.getDate() + getMaxDurasi() - 1);
                    return maxDate.toISOString().slice(0,10);
                }
                return null;
            })(),
            disable: penuh,
            locale: "id",
            onChange: function(selectedDates, dateStr) {
                validateTanggalPenuh('tgl_akhir', 'pesan_tgl_akhir');
                validateTanggalRange();
                updateRingkasan();
            }
        });
    }

    // Validasi tanggal penuh
    function validateTanggalPenuh(inputId, pesanId) {
        const tgl = document.getElementById(inputId).value;
        const penuhDates = getTanggalPenuh();
        const pesanElement = document.getElementById(pesanId);
        
        if (tgl && penuhDates.includes(tgl)) {
            const saran = getNextAvailableDate(tgl, penuhDates);
            pesanElement.innerHTML = `Tanggal ini penuh! ${saran ? 'Coba: ' + formatTanggal(saran) : ''}`;
            return false;
        } else {
            pesanElement.innerHTML = '';
            return true;
        }
    }
    
    function validateTanggalRange() {
        const mulai = tglMulaiInput.value;
        const akhir = tglAkhirInput.value;
        if (mulai && akhir) {
            const start = new Date(mulai);
            const end = new Date(akhir);
            const durasi = Math.floor((end - start) / (1000*60*60*24)) + 1;
            displayDurasi.value = durasi + ' hari';
            
            if (end < start) {
                pesanAkhir.innerHTML = 'Tanggal akhir tidak boleh sebelum tanggal mulai';
                return false;
            }
        }
        pesanAkhir.innerHTML = '';
        return true;
    }
    
    function validateJumlahPeserta() {
        const jumlah = parseInt(jumlahPeserta.value) || 0;
        const opt = paketSelect.options[paketSelect.selectedIndex];
        const minPeserta = parseInt(opt?.getAttribute('data-min-peserta') || 1);
        if (jumlah < minPeserta) {
            alert(`Jumlah peserta minimal ${minPeserta} orang`);
            return false;
        }
        return true;
    }

    // Update ringkasan
    function updateRingkasan() {
        const opt = paketSelect.options[paketSelect.selectedIndex];
        const namaPaket = opt ? opt.text.split(' (Diskon')[0] : '-';
        const jumlah = parseInt(jumlahPeserta.value) || 0;
        const tglMulai = tglMulaiInput.value;
        const tglAkhir = tglAkhirInput.value;
        let harga = parseInt(opt?.getAttribute('data-harga')) || 0;
        let lama = 0;
        let diskonText = '';
        
        const diskonData = opt ? JSON.parse(opt.getAttribute('data-diskon')) : [];
        if (diskonData.length > 0 && tglMulai) {
            const tgl = new Date(tglMulai);
            const diskonAktif = diskonData.find(d => {
                return (!d.mulai || new Date(d.mulai) <= tgl) && (!d.akhir || tgl <= new Date(d.akhir));
            });
            if (diskonAktif) {
                const diskonPersen = diskonAktif.persen || 0;
                diskonText = `${diskonPersen}% (Hemat ${formatRupiah(harga * diskonPersen / 100)})`;
                summaryDiskonContainer.style.display = 'flex';
                summaryDiskon.innerText = diskonText;
                harga = harga * (100 - diskonPersen) / 100;
            } else {
                summaryDiskonContainer.style.display = 'none';
            }
        } else {
            summaryDiskonContainer.style.display = 'none';
        }

        // Hitung durasi
        if (tglMulai && tglAkhir) {
            const start = new Date(tglMulai);
            const end = new Date(tglAkhir);
            lama = Math.floor((end - start) / (1000*60*60*24)) + 1;
            if (lama < 1) lama = 1;
            
            // Validasi durasi maksimal
            const maxDurasi = getMaxDurasi();
            if (lama > maxDurasi) {
                lama = maxDurasi;
                const newEndDate = new Date(start);
                newEndDate.setDate(newEndDate.getDate() + maxDurasi - 1);
                tglAkhirPicker.setDate(newEndDate);
            }
            
            displayDurasi.value = lama + ' hari';
        } else {
            displayDurasi.value = '-';
        }

        // Hitung total
        let total = harga * jumlah * lama;

        // Update tampilan ringkasan
        summaryPaket.innerText = namaPaket;
        summaryPeserta.innerText = jumlah;
        summaryTanggal.innerText = tglMulai && tglAkhir ? 
            `${formatTanggal(tglMulai)} - ${formatTanggal(tglAkhir)}` : '-';
        summaryLama.innerText = lama;
        summaryHarga.innerText = formatRupiah(harga);
        summaryTotal.innerText = formatRupiah(total);
        totalBayarInput.value = total;
    }

    // Event listeners
    paketSelect.addEventListener('change', function() {
        setupFlatpickr();
        validateTanggalPenuh('tgl_mulai', 'pesan_tgl_mulai');
        validateTanggalPenuh('tgl_akhir', 'pesan_tgl_akhir');
        updateRingkasan();
        
        const opt = this.options[this.selectedIndex];
        const minPeserta = opt?.getAttribute('data-min-peserta') || 1;
        jumlahPeserta.min = minPeserta;
    });
    
    jumlahPeserta.addEventListener('input', function() {
        if (this.value < 1) this.value = 1;
        updateRingkasan();
    });
    
    formBooking.addEventListener('submit', function(e) {
        if (!validateJumlahPeserta()) {
            e.preventDefault();
            return;
        }
        if (!validateTanggalRange()) {
            e.preventDefault();
            return;
        }
        if (!validateTanggalPenuh('tgl_mulai', 'pesan_tgl_mulai') || 
            !validateTanggalPenuh('tgl_akhir', 'pesan_tgl_akhir')) {
            e.preventDefault();
            alert('Harap pilih tanggal yang tersedia');
            return;
        }
        if (!confirm('Apakah data reservasi sudah benar?')) {
            e.preventDefault();
        }
    });

    // Inisialisasi awal
    setupFlatpickr();
    updateRingkasan();
});
</script>
@endpush
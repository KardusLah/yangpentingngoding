{{-- filepath: resources/views/fe/reservasi/index.blade.php --}}
@extends('fe.master')

{{-- @section('navbar')
    @include('fe.navbar')
@endsection --}}

@section('content')
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
                    <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" required>
                    <div id="pesan_tgl_mulai" class="text-danger small"></div>
                </div>
                <div class="mb-3">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" required>
                    <div id="pesan_tgl_akhir" class="text-danger small"></div>
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
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Ringkasan Pemesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold">Paket Wisata:</div>
                            <div id="summary_paket" class="text-end">-</div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold">Jumlah Peserta:</div>
                            <div id="summary_peserta">-</div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold">Tanggal:</div>
                            <div id="summary_tanggal" class="text-end">-</div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold">Durasi:</div>
                            <div><span id="summary_lama">-</span> hari</div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <div class="fw-bold">Harga per Pack:</div>
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
                        
                        {{-- Informasi penting --}}
                        <div class="alert alert-warning mt-4 small">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Harap periksa kembali data pemesanan sebelum melanjutkan pembayaran.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="total_bayar" id="total_bayar">
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data tanggal yang sudah penuh dari controller
    const tanggalPenuh = @json($tanggalPenuh ?? []);
    
    // Elemen form
    const formBooking = document.getElementById('formBooking');
    const paketSelect = document.getElementById('id_paket');
    const jumlahPeserta = document.getElementById('jumlah_peserta');
    const tglMulaiInput = document.getElementById('tgl_mulai');
    const tglAkhirInput = document.getElementById('tgl_akhir');
    const metodePembayaran = document.getElementById('metode_pembayaran');
    const buktiTransferGroup = document.getElementById('bukti_transfer_group');
    
    // Elemen ringkasan
    const summaryPaket = document.getElementById('summary_paket');
    const summaryPeserta = document.getElementById('summary_peserta');
    const summaryTanggal = document.getElementById('summary_tanggal');
    const summaryLama = document.getElementById('summary_lama');
    const summaryHarga = document.getElementById('summary_harga');
    const summaryDiskon = document.getElementById('summary_diskon');
    const summaryDiskonContainer = document.getElementById('summary_diskon_container');
    const summaryTotal = document.getElementById('summary_total');
    const totalBayarInput = document.getElementById('total_bayar');
    
    // Set tanggal minimum (hari ini)
    const today = new Date().toISOString().split('T')[0];
    tglMulaiInput.min = today;
    tglAkhirInput.min = today;

    // ================= FUNGSI BANTU =================
    
    /**
     * Format tanggal menjadi format pendek (contoh: 12 Jan)
     */
    function formatTanggal(dateString) {
        if (!dateString) return '-';
        const options = { day: 'numeric', month: 'short' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }
    
    /**
     * Format angka ke Rupiah
     */
    function formatRupiah(angka) {
        if (isNaN(angka) || angka === null) return '-';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(angka);
    }
    
    /**
     * Dapatkan tanggal yang penuh untuk paket yang dipilih
     */
    function getTanggalPenuh() {
        const paketId = paketSelect.value;
        return tanggalPenuh[paketId] || [];
    }
    
    /**
     * Cari tanggal tersedia berikutnya jika tanggal yang dipilih penuh
     */
    function getNextAvailableDate(startDate, penuhDates) {
        let date = new Date(startDate);
        for (let i = 0; i < 365; i++) {
            const ymd = date.toISOString().slice(0,10);
            if (!penuhDates.includes(ymd)) return ymd;
            date.setDate(date.getDate() + 1);
        }
        return null;
    }
    
    /**
     * Dapatkan durasi maksimal berdasarkan paket yang dipilih
     */
    function getMaxDurasi() {
        const opt = paketSelect.options[paketSelect.selectedIndex];
        return parseInt(opt?.getAttribute('data-durasi') || 1);
    }
    
    // ================= FUNGSI VALIDASI =================
    
    /**
     * Validasi apakah tanggal yang dipilih penuh
     */
    function validateTanggalPenuh(inputId, pesanId) {
        const tgl = document.getElementById(inputId).value;
        const penuhDates = getTanggalPenuh();
        const pesanElement = document.getElementById(pesanId);
        
        if (tgl && penuhDates.includes(tgl)) {
            const saran = getNextAvailableDate(tgl, penuhDates);
            pesanElement.innerHTML = `Tanggal ini penuh! Silakan pilih: <b>${saran ? formatTanggal(saran) : 'tanggal lain'}</b>`;
            return false;
        } else {
            pesanElement.innerHTML = '';
            return true;
        }
    }
    
    /**
     * Validasi tanggal akhir tidak boleh sebelum tanggal mulai
     */
    function validateTanggalRange() {
        const mulai = tglMulaiInput.value;
        const akhir = tglAkhirInput.value;
        const pesanAkhir = document.getElementById('pesan_tgl_akhir');
        
        if (mulai && akhir) {
            if (new Date(akhir) < new Date(mulai)) {
                pesanAkhir.innerHTML = 'Tanggal akhir tidak boleh sebelum tanggal mulai';
                return false;
            }
        }
        return true;
    }
    
    /**
     * Validasi jumlah peserta minimal
     */
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
    
    // ================= FUNGSI UPDATE RINGKASAN =================
    
    /**
     * Update semua informasi di ringkasan pemesanan
     */
    function updateRingkasan() {
        const opt = paketSelect.options[paketSelect.selectedIndex];
        const namaPaket = opt ? opt.text.split(' (Diskon')[0] : '-';
        const hargaAsli = parseInt(opt?.getAttribute('data-harga-asli')) || 0;
        const jumlah = parseInt(jumlahPeserta.value) || 0;
        const tglMulai = tglMulaiInput.value;
        const tglAkhir = tglAkhirInput.value;
        
        let harga = parseInt(opt?.getAttribute('data-harga')) || hargaAsli;
        let lama = 0;
        let diskonText = '';
        
        // Hitung diskon jika ada
        const diskonData = opt ? JSON.parse(opt.getAttribute('data-diskon')) : [];
        if (diskonData.length > 0 && tglMulai) {
            const tgl = new Date(tglMulai);
            const diskonAktif = diskonData.find(d => {
                return new Date(d.mulai) <= tgl && tgl <= new Date(d.akhir);
            });
            
            if (diskonAktif) {
                harga = diskonAktif.harga_diskon;
                diskonText = `${diskonAktif.persen}% (Hemat ${formatRupiah(hargaAsli - harga)})`;
                summaryDiskonContainer.style.display = 'flex';
                summaryDiskon.innerText = diskonText;
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
                // Update tanggal akhir secara otomatis
                const newEndDate = new Date(start);
                newEndDate.setDate(newEndDate.getDate() + maxDurasi - 1);
                tglAkhirInput.value = newEndDate.toISOString().split('T')[0];
            }
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
    
    // ================= EVENT LISTENERS =================
    
    // Validasi saat paket berubah
    paketSelect.addEventListener('change', function() {
        validateTanggalPenuh('tgl_mulai', 'pesan_tgl_mulai');
        validateTanggalPenuh('tgl_akhir', 'pesan_tgl_akhir');
        updateRingkasan();
        
        // Set jumlah peserta minimal
        const opt = this.options[this.selectedIndex];
        const minPeserta = opt?.getAttribute('data-min-peserta') || 1;
        jumlahPeserta.min = minPeserta;
    });
    
    // Validasi tanggal mulai
    tglMulaiInput.addEventListener('change', function() {
        if (this.value) {
            tglAkhirInput.min = this.value;
            if (!tglAkhirInput.value || new Date(tglAkhirInput.value) < new Date(this.value)) {
                tglAkhirInput.value = this.value;
            }
        }
        
        validateTanggalPenuh('tgl_mulai', 'pesan_tgl_mulai');
        validateTanggalRange();
        updateRingkasan();
    });
    
    // Validasi tanggal akhir
    tglAkhirInput.addEventListener('change', function() {
        validateTanggalPenuh('tgl_akhir', 'pesan_tgl_akhir');
        validateTanggalRange();
        updateRingkasan();
    });
    
    // Update saat jumlah peserta berubah
    jumlahPeserta.addEventListener('input', function() {
        if (this.value < 1) this.value = 1;
        updateRingkasan();
    });
    
    // Toggle bukti transfer
    metodePembayaran.addEventListener('change', function() {
        buktiTransferGroup.style.display = this.value === 'transfer' ? 'block' : 'none';
    });
    
    // Validasi form sebelum submit
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
        
        // Konfirmasi sebelum submit
        if (!confirm('Apakah data reservasi sudah benar?')) {
            e.preventDefault();
        }
    });
    
    // Inisialisasi awal
    updateRingkasan();
});
</script>
@endsection
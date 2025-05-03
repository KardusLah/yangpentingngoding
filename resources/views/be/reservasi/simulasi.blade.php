@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Simulasi Pemesanan Paket Wisata</h2>
    <form method="POST" action="{{ route('reservasi.simulasi') }}">
        @csrf
        <div class="mb-2">
            <label>Paket Wisata</label>
            <select name="id_paket" id="id_paket" class="form-control" required>
                <option value="">Pilih Paket</option>
                @foreach($paket as $p)
                    <option value="{{ $p->id }}" {{ old('id_paket') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_paket }} (Rp{{ number_format($p->harga_per_pack) }}, {{ $p->durasi }} hari)
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required value="{{ old('tanggal_mulai') }}">
        </div>
        <button class="btn btn-info">Simulasikan</button>
    </form>

    @if($simulasi)
        <div class="mt-4">
            <h5>Hasil Simulasi:</h5>
            <ul>
                <li>Paket: <strong>{{ $simulasi['paket']->nama_paket ?? '-' }}</strong></li>
                <li>Harga: <strong>Rp{{ number_format($simulasi['harga']) }}</strong></li>
                <li>Durasi: <strong>{{ $simulasi['paket']->durasi ?? 1 }} hari</strong></li>
                <li>Tanggal: <strong>{{ $simulasi['tanggal_mulai'] }}</strong> s/d <strong>{{ $simulasi['tanggal_akhir'] }}</strong></li>
                <li>
                    Status: 
                    @if($simulasi['bisa'])
                        <span class="badge bg-success">Bisa Dipesan</span>
                    @else
                        <span class="badge bg-danger">Penuh, silakan pilih tanggal lain</span>
                    @endif
                </li>
            </ul>
            <div>
                <strong>Tanggal yang dicek:</strong>
                <ul>
                    @foreach($simulasi['tanggalCek'] as $tgl)
                        <li>
                            {{ $tgl }}
                            @if(isset($tanggalPenuh[$simulasi['paket']->id]) && in_array($tgl, $tanggalPenuh[$simulasi['paket']->id]))
                                <span class="badge bg-danger">Penuh</span>
                            @else
                                <span class="badge bg-success">Tersedia</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const tanggalPenuh = @json($tanggalPenuh);

document.getElementById('id_paket').addEventListener('change', function() {
    document.getElementById('tanggal_mulai').value = '';
});

document.getElementById('tanggal_mulai').addEventListener('input', function(e) {
    const idPaket = document.getElementById('id_paket').value;
    const inputDate = e.target.value;
    if (!idPaket) return;
    const durasi = @json($paket->pluck('durasi','id'));
    let bisa = true;
    let tgl = new Date(inputDate);
    for(let i=0; i<(durasi[idPaket]||1); i++) {
        let cek = tgl.toISOString().slice(0,10);
        if(tanggalPenuh[idPaket] && tanggalPenuh[idPaket].includes(cek)) {
            alert('Tanggal '+cek+' sudah penuh, silakan pilih tanggal lain.');
            e.target.value = '';
            break;
        }
        tgl.setDate(tgl.getDate()+1);
    }
});
</script>
@endpush
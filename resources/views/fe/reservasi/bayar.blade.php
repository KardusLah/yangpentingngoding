{{-- filepath: resources/views/fe/reservasi/bayar.blade.php --}}
@extends('fe.master')

@section('content')
<div class="container py-5">
    <h2>Pembayaran Reservasi</h2>
    <div class="alert alert-info">Klik tombol di bawah untuk melakukan pembayaran.</div>
    <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button').onclick = function(){
    window.snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){ window.location.href = '/reservasi/sukses'; },
        onPending: function(result){ window.location.href = '/reservasi/pending'; },
        onError: function(result){ alert('Pembayaran gagal'); }
    });
};
</script>
@endsection
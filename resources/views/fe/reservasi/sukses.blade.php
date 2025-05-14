{{-- filepath: resources/views/fe/reservasi/sukses.blade.php --}}
{{-- @extends('fe.master')

@section('content')
<div class="container py-5 text-center">
    <h2 class="mb-4">Reservasi Berhasil!</h2>
    <p>Silakan lanjutkan pembayaran untuk menyelesaikan reservasi Anda.</p>
    <button id="pay-button" class="btn btn-success btn-lg mb-3">Bayar Sekarang</button>
    <br>
    <a href="{{ route('fe.reservasi.index') }}" class="btn btn-secondary">Kembali ke Daftar Reservasi</a>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
document.getElementById('pay-button').onclick = function(){
    window.snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            alert('Pembayaran berhasil!');
            window.location.href = "{{ route('fe.reservasi.index') }}";
        },
        onPending: function(result){
            alert('Transaksi belum selesai. Silakan selesaikan pembayaran.');
        },
        onError: function(result){
            alert('Pembayaran gagal atau dibatalkan.');
        },
        onClose: function(){
            alert('Anda menutup popup pembayaran tanpa menyelesaikan transaksi.');
        }
    });
};
</script>
@endpush --}}
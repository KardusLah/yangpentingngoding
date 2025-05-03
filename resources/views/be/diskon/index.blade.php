<!-- filepath: resources/views/be/diskon/index.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2>Manajemen Diskon Paket Wisata</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('diskon.update') }}">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Paket Wisata</th>
                    <th>Aktifkan Diskon</th>
                    <th>Persen Diskon (%)</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paket as $p)
                <tr>
                    <td>
                        {{ $p->nama_paket }}
                        <input type="hidden" name="paket_id[]" value="{{ $p->id }}">
                    </td>
                    <td>
                        <input type="checkbox" name="aktif[]" value="{{ $p->id }}"
                            {{ isset($diskon[$p->id]) && $diskon[$p->id]->aktif ? 'checked' : '' }}>
                    </td>
                    <td>
                        <input type="number" name="persen[{{ $p->id }}]" min="0" max="100" class="form-control"
                            value="{{ isset($diskon[$p->id]) ? $diskon[$p->id]->persen : 0 }}">
                    </td>
                    <td>
                        <input type="date" name="tanggal_mulai[{{ $p->id }}]" class="form-control"
                            value="{{ isset($diskon[$p->id]) && $diskon[$p->id]->tanggal_mulai ? date('Y-m-d', strtotime($diskon[$p->id]->tanggal_mulai)) : '' }}">
                    </td>
                    <td>
                        <input type="date" name="tanggal_akhir[{{ $p->id }}]" class="form-control"
                            value="{{ isset($diskon[$p->id]) && $diskon[$p->id]->tanggal_akhir ? date('Y-m-d', strtotime($diskon[$p->id]->tanggal_akhir)) : '' }}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary">Simpan Diskon</button>
    </form>
</div>
@endsection
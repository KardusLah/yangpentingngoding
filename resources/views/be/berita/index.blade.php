<!-- filepath: resources/views/be/berita/index.blade.php -->
@extends('be.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-3">Manajemen Berita & Promosi</h2>
    <a href="{{ route('berita.create') }}" class="btn btn-primary mb-3">Tambah Berita</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tanggal Post</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($berita as $i => $b)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $b->judul }}</td>
                <td>{{ $b->kategori->kategori_berita ?? '-' }}</td>
                <td>{{ $b->tgl_post }}</td>
                <td>
                    @if($b->foto)
                        <img src="{{ asset('storage/'.$b->foto) }}" width="60" style="cursor:pointer"
                             onclick="showImgPreview('{{ asset('storage/'.$b->foto) }}')">
                    @endif
                </td>
                <td>
                    <a href="{{ route('berita.edit', $b->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('berita.destroy', $b->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Modal Preview Gambar -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-labelledby="imgPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <img id="imgPreview" src="" alt="Preview" style="max-width:100%;max-height:70vh;">
        </div>
      </div>
    </div>
  </div>
  <script>
  function showImgPreview(src) {
      document.getElementById('imgPreview').src = src;
      var myModal = new bootstrap.Modal(document.getElementById('imgPreviewModal'));
      myModal.show();
  }
  </script>
@endsection
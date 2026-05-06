@extends(
    Auth::user()->roles == 'admin' ? 'layout.admin' : 
    (Auth::user()->roles == 'petugas' ? 'layout.petugas' : 'layout.default')
)

@section('title', 'Edit Informasi')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Informasi</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('informasi.update', $informasi->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="judul">Judul Informasi</label>
                <input type="text" name="judul" id="judul" class="form-control" required value="{{ $informasi->judul }}">
            </div>
            <div class="form-group">
                <label for="foto_informasi" class="form-label">Foto Informasi</label>
                <input class="form-control" type="file" name="foto_informasi" id="foto_informasi">
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required>{{ $informasi->deskripsi }}</textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('informasi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@include('sweetalert::alert')

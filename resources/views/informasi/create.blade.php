@extends(
    Auth::user()->roles == 'admin' ? 'layout.admin' : 
    (Auth::user()->roles == 'petugas' ? 'layout.petugas' : 'layout.default')
)

@section('title', 'Tambah Informasi')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Informasi Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('informasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="judul">Judul Informasi</label>
                <input type="text" name="judul" id="judul" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="foto_informasi" class="form-label">Foto Informasi</label>
                <input class="form-control" type="file" name="foto_informasi" id="foto_informasi" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('informasi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@include('sweetalert::alert')

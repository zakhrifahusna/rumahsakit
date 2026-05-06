@extends(
    Auth::user()->roles == 'admin' ? 'layout.admin' : 
    (Auth::user()->roles == 'petugas' ? 'layout.petugas' : 
    (Auth::user()->roles == 'kepala_rs' ? 'layout.kepala_rs' : 'layout.default'))
)

@section('title', 'Tambah Dokter')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Dokter Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('dokter.add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama_dokter">Nama Dokter</label>
                <input type="text" name="nama_dokter" id="nama_dokter" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="poliklinik_id">Nama Poliklinik</label>
                <select name="poliklinik_id" id="poliklinik_id" class="form-control" required>
                    @foreach($poliklinik as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_poliklinik }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="hari_praktek">Hari Praktek</label>
                <select name="hari_praktek[]" id="hari_praktek" class="form-control" multiple required>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                    <option value="Minggu">Minggu</option>
                </select>
            </div>            
            <div class="form-group">
                <label for="foto_dokter" class="form-label">Foto Profil</label>
                <input class="form-control" type="file" name="foto_dokter" id="foto_dokter">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@include('sweetalert::alert')

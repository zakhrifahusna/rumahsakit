@extends(
    Auth::user()->roles == 'admin' ? 'layout.admin' : 
    (Auth::user()->roles == 'petugas' ? 'layout.petugas' : 
    (Auth::user()->roles == 'kepala_rs' ? 'layout.kepala_rs' : 'layout.default'))
)

@section('title', 'Detail Dokter')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800 text-center">Detail Dokter</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('dokter.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body text-center">
        @if ($dokter)
            <div class="form-group">
                <img src="{{ asset('storage/foto_dokter/' . $dokter->foto_dokter) }}" alt="Foto Dokter" class="img-fluid rounded mb-4" width="200" height="200">
            </div>
            <div class="form-group">
                <h5>Nama Dokter:</h5>
                <p>{{ $dokter->nama_dokter }}</p>
            </div>
            <div class="form-group">
                <h5>Poli:</h5>
                <p>{{ $dokter->poliklinik->nama_poliklinik }}</p>
            </div>
            <div class="form-group">
                <h5>Hari Praktek:</h5>
                <p>{{ $dokter->formatted_hari_praktek }}</p>
            </div>
            <div class="form-group">
                <h5>Rating:</h5>
                <p>{{ $averageRating }}/5</p>
            </div>
        @else
            <p>Dokter tidak ditemukan.</p>
        @endif
    </div>
</div>
@endsection

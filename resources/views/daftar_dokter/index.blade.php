@extends(
    Auth::user()->roles == 'admin' ? 'layout.admin' : 
    (Auth::user()->roles == 'pasien' ? 'layout.pasien' : 
    (Auth::user()->roles == 'petugas' ? 'layout.petugas' : 
    (Auth::user()->roles == 'kepala_rs' ? 'layout.kepala_rs' : 'layout.default')))
)

@section('title', 'Daftar Dokter')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Daftar Dokter</h1>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif

<!-- Daftar Dokter per Poliklinik -->
@foreach ($polikliniks as $poliklinik)
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ $poliklinik->nama_poliklinik }}</h6>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($poliklinik->dokters as $dokter)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/foto_dokter/' . $dokter->foto_dokter) }}" class="card-img-top" alt="Foto Dokter" style="height: 300px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 1rem; font-weight: bold;">{{ $dokter->nama_dokter }}</h5>
                            <p class="card-text" style="font-size: 0.875rem;">
                                <strong>Hari Praktek:</strong> {{ $dokter->formatted_hari_praktek }}<br>
                                <strong>Rating:</strong> {{ round($dokter->ratings->avg('rating'), 2) ?? 'Belum ada rating' }}/5
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach

@include('sweetalert::alert')
@endsection

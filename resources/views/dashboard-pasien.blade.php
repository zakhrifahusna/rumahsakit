@extends('layout.pasien')

@section('title', 'Informasi Terkini')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h2>Informasi Terkini</h2>
            </div>
            @foreach ($informasi as $item)
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">{{ $item->judul }}</h3>
                            <p class="text-muted">Diperbarui: {{ $item->created_at->timezone('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }}</p> 
                            <div class="row">
                                <div class="col-md-4">
                                    @if ($item->foto_informasi && file_exists(public_path('storage/foto_informasi/' . $item->foto_informasi)))
                                        <img src="{{ asset('storage/foto_informasi/' . $item->foto_informasi) }}" alt="Foto Informasi" class="img-fluid rounded mb-4" width="300">
                                    @else
                                        <p>Gambar tidak ditemukan atau path salah</p>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <p class="card-text">{{ Str::limit($item->deskripsi, 150) }}</p>
                                    <div class="text-end">
                                        <a href="{{ route('informasi.show', $item->id) }}" class="text-primary position-absolute" 
                                            style="right: 0; bottom: 0; padding-right: 15px; padding-bottom: 10px;">>> See More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @include('sweetalert::alert')
        </div>
    </div>
@endsection

@extends('layout.pasien')

@section('title', $item->judul)

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <a href="{{ route('dashboard-pasien') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h2 class="mt-4">{{ $item->judul }}</h2>
                        <p class="text-muted">Diperbarui: {{ $item->created_at->timezone('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4 text-center">
                        @if ($item->foto_informasi && file_exists(public_path('storage/foto_informasi/' . $item->foto_informasi)))
                            <img src="{{ asset('storage/foto_informasi/' . $item->foto_informasi) }}" alt="Foto Informasi" class="img-fluid rounded mb-4" style="max-width: 50%;">
                        @else
                            <p>Gambar tidak ditemukan atau path salah</p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p>{{ $item->deskripsi }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

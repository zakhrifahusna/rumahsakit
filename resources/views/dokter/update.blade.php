@extends('layout.admin')

@section('title', 'Edit Dokter')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Dokter</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('dokter.update', $dokter->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="nama_dokter">Nama Dokter</label>
                <input type="text" name="nama_dokter" id="nama_dokter" class="form-control" required value="{{ $dokter->nama_dokter }}">
            </div>
            <div class="form-group">
                <label for="poliklinik_id">Nama Poliklinik</label>
                <input type="text" id="poliklinik_id" class="form-control" value="{{ $dokter->poliklinik->nama_poliklinik }}" readonly>
                <input type="hidden" name="poliklinik_id" value="{{ $dokter->poliklinik_id }}">
            </div>      
            <div class="form-group">
                <label for="hari_praktek">Hari Praktek</label>
                <select name="hari_praktek[]" id="hari_praktek" class="form-control" multiple required>
                    @php
                        $hariPraktek = json_decode($dokter->hari_praktek);
                    @endphp
                    <option value="Senin" {{ in_array('Senin', (array) $hariPraktek) ? 'selected' : '' }}>Senin</option>
                    <option value="Selasa" {{ in_array('Selasa', (array) $hariPraktek) ? 'selected' : '' }}>Selasa</option>
                    <option value="Rabu" {{ in_array('Rabu', (array) $hariPraktek) ? 'selected' : '' }}>Rabu</option>
                    <option value="Kamis" {{ in_array('Kamis', (array) $hariPraktek) ? 'selected' : '' }}>Kamis</option>
                    <option value="Jumat" {{ in_array('Jumat', (array) $hariPraktek) ? 'selected' : '' }}>Jumat</option>
                    <option value="Sabtu" {{ in_array('Sabtu', (array) $hariPraktek) ? 'selected' : '' }}>Sabtu</option>
                    <option value="Minggu" {{ in_array('Minggu', (array) $hariPraktek) ? 'selected' : '' }}>Minggu</option>
                </select>
            </div>
            <div class="form-group">
                <label for="foto_dokter" class="form-label">Foto Profil</label>
                <input class="form-control" type="file" name="foto_dokter" id="foto_dokter">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('dokter.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@include('sweetalert::alert')

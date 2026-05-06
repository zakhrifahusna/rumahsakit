@extends(
    Auth::user()->roles == 'admin' ? 'layout.admin' : 
    (Auth::user()->roles == 'petugas' ? 'layout.petugas' : 
    (Auth::user()->roles == 'kepala_rs' ? 'layout.kepala_rs' : 'layout.default'))
)

@section('title', 'Jadwal Poliklinik')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Jadwal Poliklinik</h1>

<!-- Date Range Filter -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Cari Berdasarkan Tanggal Praktek</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('jadwalpoliklinik.index') }}" method="GET" class="row">
            <div class="col-md-3 mb-3">
                <label for="start_date">Dari Tanggal</label>
                <div class="input-group">
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request()->input('start_date') }}">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="end_date">Sampai Tanggal</label>
                <div class="input-group">
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request()->input('end_date') }}">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="search">Cari</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" value="{{ request()->input('search') }}" placeholder="Cari...">
                </div>
            </div>
            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm mr-1"><i class="fas fa-search"></i> Search</button>
                <a href="{{ route('jadwalpoliklinik.index') }}" class="btn btn-secondary btn-sm mr-1"><i class="fas fa-sync-alt"></i> Refresh</a>
                <a href="{{ route('laporan.jadwalpoliklinik', ['start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'search' => request()->input('search')]) }}" class="btn btn-danger btn-sm mr-1"><i class="fas fa-file-pdf"></i> PDF</a>                
                <a href="{{ route('jadwalpoliklinik.excel', ['start_date' => request()->input('start_date'), 'end_date' => request()->input('end_date'), 'search' => request()->input('search')]) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Excel</a>              
            </div>
        </form>               
    </div>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('jadwalpoliklinik.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Dokter</th>
                        <th>Nama Poliklinik</th>
                        <th>Profil Dokter</th>
                        <th>Tanggal Praktek</th>
                        <th>Jam Praktek</th>
                        <th>Jumlah</th>
                        @if (in_array(Auth::user()->roles, ['admin','petugas']))
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($jadwalpoliklinik as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->kode_jadwalpoliklinik }}</td>
                        <td>{{ $item->dokter->nama_dokter }}</td>
                        <td>{{ $item->dokter->poliklinik->nama_poliklinik }}</td>
                        <td>
                            <img src="{{ asset('storage/foto_dokter/' . $item->dokter->foto_dokter) }}" alt="Foto Dokter" width="50" height="50">
                        </td>
                        <td>{{ $item->tanggal_praktek->format('d-m-Y') }}</td>
                        <td>{{ $item->jam_mulai->format('H:i') }} - {{ $item->jam_selesai->format('H:i') }}</td>
                        
                        <td>{{ $item->jumlah }}</td>
                        @if (in_array(Auth::user()->roles, ['admin','petugas']))
                        <td class="d-flex">
                            <a href="{{ route('jadwalpoliklinik.edit', $item->id) }}" class="btn btn-warning btn-sm mr-1">Edit</a>
                            <form action="{{ route('jadwalpoliklinik.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete" onclick="deleteJadwal({{ $item->id }})">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                    @include('sweetalert::alert')
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Sertakan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- Sertakan file JavaScript khusus -->
<script src="{{ asset('js/sweetalert.js') }}"></script>
@endsection

@extends(
    Auth::user()->roles == 'admin' ? 'layout.admin' : 
    (Auth::user()->roles == 'petugas' ? 'layout.petugas' : 
    (Auth::user()->roles == 'kepala_rs' ? 'layout.kepala_rs' : 'layout.default'))
)

@section('title', 'Dokter')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Dokter</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        @if (Auth::user()->roles == 'admin')
        <a href="{{ route('dokter.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dokter</th>
                        <th>Poli</th>
                        <th>Hari Praktek</th>
                        <th>Profil</th>
                        <th>Rating</th>
                        <th>Total Pasien</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($dokter as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->nama_dokter }}</td>
                        <td>{{ $item->poliklinik->nama_poliklinik }}</td>
                        <td>{{ $item->formatted_hari_praktek }}</td>
                        <td>
                            <img src="{{ asset('storage/foto_dokter/' . $item->foto_dokter) }}" alt="Foto Dokter" width="50" height="50">
                        </td>
                        <td>{{ round($item->ratings->avg('rating'), 2) ?? 'Belum ada rating' }}</td>
                        <td>{{ $item->antrians_count }}</td>
                        @if (Auth::user()->roles == 'admin')
                        <td>
                            <a href="{{ route('dokter.show', $item->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('dokter.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('dokter.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete">Hapus</button>
                            </form>
                        </td>
                        @endif
                        @if (in_array(Auth::user()->roles, ['petugas', 'kepala_rs']))
                        <td>
                            <a href="{{ route('dokter.show', $item->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
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


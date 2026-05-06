@extends('layout.pasien')

@section('title', 'Antrian Poliklinik')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Riwayat Pendaftaran</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('riwayat.pasien') }}" class="btn btn-danger btn-sm mr-1"><i class="fas fa-file-pdf"></i> PDF</a>        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Jadwal Poliklinik</th>
                        <th>Kode Antrian</th>
                        <th>No Antrian</th>
                        <th>Nama Dokter</th>
                        <th>Poliklinik</th>
                        <th>Penjamin</th>
                        <th>Tanggal Berobat</th>
                        <th>Tanggal Reservasi</th>
                        <th>Scan Surat Rujukan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($antrian as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->kode_jadwalpoliklinik }}</td>
                        <td>{{ $item->kode_antrian }}</td>
                        <td>{{ $item->no_antrian }}</td>
                        <td>{{ $item->nama_dokter }}</td>
                        <td>{{ $item->poliklinik }}</td>
                        <td>{{ $item->penjamin }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_berobat)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_reservasi)->format('d-m-Y') }}</td>
                        <td>
                            @if($item->scan_surat_rujukan)
                                <a href="{{ asset('storage/' . str_replace('public/', '', $item->scan_surat_rujukan)) }}" target="_blank">Lihat/Unduh</a>
                            @else
                                Tidak ada file
                            @endif
                        </td>
                        <td>
                            @if($item && $item->id)
                                <a href="{{ route('generate.antrian', $item->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-print"></i></a>
                                <a href="javascript:void(0)" onclick="beriNilai('{{ $item->jadwalpoliklinik->dokter_id }}', '{{ $item->jadwalpoliklinik->dokter->nama_dokter }}', '{{ $item->id }}')" class="btn btn-warning btn-sm">
                                    Beri Nilai
                                </a>
                            @else
                                <span>Data tidak ditemukan</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal for Scan Surat Rujukan -->
                    <div class="modal fade" id="modalScanRujukan{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel{{ $item->id }}">Scan Surat Rujukan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/' . $item->scan_surat_rujukan) }}" class="img-fluid mb-3">
                                    <a href="{{ asset('storage/' . $item->scan_surat_rujukan) }}" class="btn btn-primary" download>Unduh</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                    @include('sweetalert::alert')
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/rating.js') }}"></script>
@endsection

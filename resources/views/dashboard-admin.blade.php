@extends('layout.admin')

@section('content')
    <p style="font-size: 24px; color: rgb(67, 66, 66);">{{ $content }}</p>

    <!-- Kartu Statistik Berdampingan -->
    <div class="row">
        <!-- Kartu Total User -->
        <div class="col-md-3">
            <div class="card mb-3" style="border-left: 5px solid #4e73df;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-l font-weight-bold text-primary text-uppercase mb-1">
                                USER</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Total Pasien -->
        <div class="col-md-3">
            <div class="card mb-3" style="border-left: 5px solid #1eda44;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-l font-weight-bold" style="color: #1eda44; text-transform: uppercase;">
                                PASIEN</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $datapasien }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Total Poliklinik -->
        <div class="col-md-3">
            <div class="card mb-3" style="border-left: 5px solid #d7ce1d;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-l font-weight-bold" style="color: #d7ce1d; text-transform: uppercase;">
                                POLIKLINIK</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $poliklinik }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Total Dokter -->
        <div class="col-md-3">
            <div class="card mb-3" style="border-left: 5px solid #5ab0db;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-l font-weight-bold" style="color: #5ab0db; text-transform: uppercase;">
                                DOKTER</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dokter }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Total Jadwal Poliklinik dan Grafik Antrian -->
    <div class="row">
        <!-- Kartu Jadwal Poliklinik dengan ukuran lebih kecil -->
        <div class="col-md-3">
            <div class="card mb-3" style="border-left: 5px solid #b3067c;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-l font-weight-bold" style="color: #b3067c; text-transform: uppercase;">
                                JADWAL POLIKLINIK</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jadwalpoliklinik }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Antrian Poliklinik dengan ukuran lebih besar -->
        <div class="col-md-9">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="font-weight-bold text-primary">Pendaftaran Poliklinik</h6>
                </div>
                <div class="card-body">
                    @php
                        // Array warna untuk progress bar
                        $colors = ['bg-danger', 'bg-warning', 'bg-success', 'bg-info', 'bg-primary', 'bg-secondary', 'bg-dark'];
                        $totalAntrian = $antrianData->sum('total');
                    @endphp

                    @foreach($antrianData as $index => $antrian)
                        @php
                            // Ambil warna dari array berdasarkan index, jika index lebih besar dari jumlah warna, maka ulangi
                            $color = $colors[$index % count($colors)];
                            $percentage = ($antrian->total / $totalAntrian) * 100;
                        @endphp

                        <h4 class="small font-weight-bold">{{ $antrian->poliklinik }} <span class="float-right">{{ number_format($percentage, 0) }}%</span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include('sweetalert::alert')
@endsection

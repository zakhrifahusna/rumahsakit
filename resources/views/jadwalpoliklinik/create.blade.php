@extends(
    Auth::user()->roles == 'admin' ? 'layout.admin' : 
    (Auth::user()->roles == 'petugas' ? 'layout.petugas' :  'layout.default')
)

@section('title', 'Tambah Jadwal Poliklinik')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Tambah Jadwal Poliklinik</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('jadwalpoliklinik.add') }}" method="POST">
            @csrf

            <!-- Dropdown untuk Poliklinik -->
            <div class="form-group">
                <label for="poliklinik_id">Pilih Poliklinik</label>
                <select name="poliklinik_id" id="poliklinik_id" class="form-control" required>
                    <option value="">Pilih Poliklinik</option>
                    @foreach($poliklinik as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_poliklinik }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown untuk Dokter -->
            <div class="form-group">
                <label for="dokter_id">Nama Dokter</label>
                <select name="dokter_id" id="dokter_id" class="form-control" required>
                    <option value="">Pilih Dokter</option>
                </select>
            </div>

            <!-- Input untuk banyak tanggal praktek (dapat ditambah secara dinamis) -->
            <div class="form-group">
                <label for="tanggal_praktek">Tanggal Praktek</label>
                <div id="tanggal-wrapper">
                    <div class="input-group mb-3">
                        <input type="date" name="tanggal_praktek[]" class="form-control" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-sm" type="button" id="addTanggal">+Tanggal Praktek</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jam dan Jumlah Pasien -->
            <div class="form-group">
                <label for="jam_mulai">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jam_selesai">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah Pasien</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('jadwalpoliklinik.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<!-- Include SweetAlert -->
@include('sweetalert::alert')

<!-- AJAX untuk mengambil data dokter -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $('#poliklinik_id').change(function() {
        var poliklinikID = $(this).val();
        if (poliklinikID) {
            $.ajax({
                url: '/getDokterByPoliklinik/' + poliklinikID,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#dokter_id').empty();
                    $('#dokter_id').append('<option value="">Pilih Dokter</option>');
                    $.each(data, function(key, value) {
                        $('#dokter_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        } else {
            $('#dokter_id').empty();
            $('#dokter_id').append('<option value="">Pilih Dokter</option>');
        }
    });

    // Menambahkan input tanggal baru secara dinamis
    $('#addTanggal').click(function() {
        $('#tanggal-wrapper').append(`
            <div class="input-group mb-3">
                <input type="date" name="tanggal_praktek[]" class="form-control" required>
                <div class="input-group-append">
                    <button class="btn btn-danger remove-tanggal" type="button">Hapus</button>
                </div>
            </div>
        `);
    });

    // Menghapus input tanggal
    $(document).on('click', '.remove-tanggal', function() {
        $(this).closest('.input-group').remove();
    });
</script>

@endsection

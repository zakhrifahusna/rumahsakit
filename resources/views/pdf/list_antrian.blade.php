<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 12px;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .header img {
            width: 50px;
            height: auto;
            position: absolute;
            top: 9px;
            left: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
        .report-title {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo.jpg') }}" alt="Logo">
        <h1>PEMERINTAH KABUPATEN PASAMAN BARAT</h1>
        <h1>RUMAH SAKIT UMUM DAERAH PASAMAN BARAT</h1>
        <p>Jalan Jenderal Sudirman, Simpang Ampek, Kabupaten Pasaman Barat 25366</p>
        <p>Telepon : (0753) 65960, Fax : (0753) 65960, Email : rsudpasamanbarat@yahoo.co.id</p>
        <hr>
        <div class="report-title">
            <strong>DATA ANTRIAN PENDAFTARAN LAYANAN RAWAT JALAN</strong>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th style="width:5px;">Kode Jadwal Poliklinik</th>
                <th>Kode Antrian</th>
                <th style="width:1px;">No Antrian</th>
                <th>Nama Pasien</th>
                <th>No Telepon</th>
                <th>Nama Dokter</th>
                <th>Poliklinik</th>
                <th>Penjamin</th>
                <th>Tanggal Berobat</th>
                <th>Tanggal Reservasi</th>
                <th>No Kartu BPJS</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($antrian as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="width:5px;">{{ $item->kode_jadwalpoliklinik }}</td>
                <td>{{ $item->kode_antrian }}</td>
                <td style="width:1px;">{{ $item->no_antrian }}</td>
                <td>{{ $item->nama_pasien }}</td>
                <td>{{ $item->no_telp }}</td>
                <td>{{ $item->nama_dokter }}</td>
                <td>{{ $item->poliklinik }}</td>
                <td>{{ $item->penjamin }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_berobat)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_reservasi)->format('d-m-Y') }}</td>
                <td>{{ $item->no_kbpjs }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
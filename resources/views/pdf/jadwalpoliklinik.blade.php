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
            <strong>DATA JADWAL POLIKLINIK LAYANAN RAWAT JALAN</strong>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Jadwal Poliklinik</th>
                <th>Nama Dokter</th>
                <th>Nama Poliklinik</th>
                <th>Tanggal Praktek</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Jumlah</th>
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
                <td>{{ \Carbon\Carbon::parse($item->tanggal_praktek)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                <td>{{ $item->jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

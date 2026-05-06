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
            font-size: 10px; 
        }
        th, td {
            border: 1px solid black;
            padding: 4px; 
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            word-wrap: break-word;
            max-width: 100px; 
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
            <strong>DATA PASIEN RAWAT JALAN</strong>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pasien</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>NIK</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>No. Kartu Berobat</th>
                <th>No. Kartu BPJS</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($dataPasien as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->nama_pasien }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->no_telp }}</td>
                <td>{{ $item->nik ?? '-' }}</td>
                <td>{{ $item->tempat_lahir ?? '-' }}</td>
                <td>{{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                <td>{{ $item->jenis_kelamin ?? '-' }}</td>
                <td>{{ $item->alamat ?? '-' }}</td>
                <td>{{ $item->no_kberobat ?? '-' }}</td>
                <td>{{ $item->no_kbpjs ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PasienExport implements FromCollection, WithHeadings
{
    protected $dataPasien;

    public function __construct($dataPasien)
    {
        $this->dataPasien = $dataPasien;
    }

    public function collection()
    {
        return $this->dataPasien;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pasien',
            'Email',
            'No. Telepon',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Alamat',
            'No. Kartu Berobat',
            'No. Kartu BPJS',
        ];
    }
}

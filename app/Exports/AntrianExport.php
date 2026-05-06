<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class AntrianExport implements FromCollection
{
    protected $antrian;

    // Tambahkan constructor untuk menerima data antrian
    public function __construct($antrian)
    {
        $this->antrian = $antrian;
    }

    // Mengembalikan koleksi data antrian
    public function collection()
    {
        return $this->antrian;
    }
}

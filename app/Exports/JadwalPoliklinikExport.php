<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JadwalPoliklinikExport implements FromCollection, WithHeadings
{
    protected $jadwalpoliklinik;

    public function __construct($jadwalpoliklinik)
    {
        $this->jadwalpoliklinik = $jadwalpoliklinik;
    }

    public function collection()
    {
        return $this->jadwalpoliklinik->map(function($item) {
            return [
                'kode_jadwalpoliklinik' => $item->kode_jadwalpoliklinik,
                'nama_dokter' => $item->dokter->nama_dokter,
                'nama_poliklinik' => $item->dokter->poliklinik->nama_poliklinik,
                'tanggal_praktek' => \Carbon\Carbon::parse($item->tanggal_praktek)->format('d-m-Y'),
                'jam_mulai' => \Carbon\Carbon::parse($item->jam_mulai)->format('H:i'),
                'jam_selesai' => \Carbon\Carbon::parse($item->jam_selesai)->format('H:i'),
                'jumlah' => $item->jumlah,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Jadwal Poliklinik',
            'Nama Dokter',
            'Nama Poliklinik',
            'Tanggal Praktek',
            'Jam Mulai',
            'Jam Selesai',
            'Jumlah',
        ];
    }
}


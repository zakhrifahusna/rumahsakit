<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    
    protected $table = 'dokter';
    
    protected $fillable = ['nama_dokter', 'poliklinik_id', 'foto_dokter', 'hari_praktek'];

    // public function getHariPraktekAttribute($value)
    // {
    //     return json_decode($value);
    // }

    public function getFormattedHariPraktekAttribute()
    {
        // Daftar hari dalam urutan
        $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Mengonversi hari_praktek menjadi array jika belum
        $hariPraktek = is_array($this->hari_praktek) ? $this->hari_praktek : json_decode($this->hari_praktek, true);

        // Pastikan hariPraktek valid dan ada dalam daftar
        $hariPraktek = array_intersect($daftarHari, (array) $hariPraktek);

        // Mengambil indeks dari hari-hari yang dipilih
        $indexHari = array_map(function($hari) use ($daftarHari) {
            return array_search($hari, $daftarHari);
        }, $hariPraktek);

        sort($indexHari); // Urutkan berdasarkan indeks hari

        // Jika indexHari kosong, kembalikan string kosong atau pesan default
        if (empty($indexHari)) {
            return 'Tidak ada hari praktek'; // Atau return '';
        }

        $rentang = [];
        $start = $indexHari[0];
        $end = $start;

        // Menentukan rentang hari
        for ($i = 1; $i < count($indexHari); $i++) {
            if ($indexHari[$i] == $end + 1) {
                $end = $indexHari[$i];
            } else {
                $rentang[] = ($start == $end) ? $daftarHari[$start] : $daftarHari[$start] . '-' . $daftarHari[$end];
                $start = $indexHari[$i];
                $end = $start;
            }
        }

        // Menambahkan rentang terakhir
        $rentang[] = ($start == $end) ? $daftarHari[$start] : $daftarHari[$start] . '-' . $daftarHari[$end];

        return implode(', ', $rentang);
    }

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'poliklinik_id');
    }
    
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'dokter_id');
    }

    public function antrians()
    {
        return $this->hasMany(Antrian::class, 'nama_dokter', 'nama_dokter');
    }

}

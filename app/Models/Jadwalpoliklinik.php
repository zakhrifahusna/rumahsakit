<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwalpoliklinik extends Model
{
    use HasFactory;

    protected $table = 'jadwalpoliklinik';

    protected $fillable = [
        'kode_jadwalpoliklinik',
        'dokter_id',
        'poliklinik_id',
        'tanggal_praktek',
        'jam_mulai',
        'jam_selesai',
        'jumlah'
    ];

    protected $dates = [
        'tanggal_praktek',
        'jam_mulai',
        'jam_selesai',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class);
    }
}

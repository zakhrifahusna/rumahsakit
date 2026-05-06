<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    use HasFactory;

    protected $table = 'poliklinik';
    protected $fillable = [
        'nama_poliklinik',
    ];

    public function antrians()
    {
        return $this->hasMany(Antrian::class, 'poliklinik', 'nama_poliklinik');
    }

    public function dokters()
    {
        return $this->hasMany(Dokter::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'rating';

    protected $fillable = [
        'dokter_id',
        'user_id',
        'antrian_id',
        'rating',
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function antrian()
    {
        return $this->belongsTo(Antrian::class);
    }
}

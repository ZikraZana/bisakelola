<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataWarga extends Model
{
    protected $table = 'data_warga';

    protected $fillable = [
        'no_kk',
        'nama_kepala_keluarga',
        'nik_kepala_keluarga',
    ];

    
}

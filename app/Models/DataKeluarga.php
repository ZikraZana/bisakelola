<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKeluarga extends Model
{
    protected $table = 'data_keluarga';

    protected $primaryKey = 'id_keluarga';

    protected $fillable = ['no_kk', 'id_blok', 'id_desil'];


    public function anggotaKeluarga()
    {
        return $this->hasMany(AnggotaKeluarga::class, 'id_keluarga', 'id_keluarga');
    }

    public function blok()
    {
        return $this->belongsTo(Blok::class, 'id_blok', 'id_blok');
    }

    public function desil()
    {
        return $this->belongsTo(Desil::class, 'id_desil', 'id_desil');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKeluarga extends Model
{
    protected $table = 'data_keluarga';

    protected $primaryKey = 'id_keluarga';

    protected $fillable = ['no_kk', 'id_admin', 'status' ,'id_blok', 'id_desil', 'foto_ktp', 'foto_kk'];


    public function anggotaKeluarga()
    {
        return $this->hasMany(AnggotaKeluarga::class, 'id_keluarga', 'id_keluarga');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public function blok()
    {
        return $this->belongsTo(Blok::class, 'id_blok', 'id_blok');
    }

    public function desil()
    {
        return $this->belongsTo(Desil::class, 'id_desil', 'id_desil');
    }
    public function penerimaBansos()
    {
        return $this->hasOne(DataPenerimaBansos::class, 'id_keluarga', 'id_keluarga');
    }
}

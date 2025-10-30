<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    protected $table = 'data_anggota_keluarga';

    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'id_keluarga',
        'nik_anggota',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'status_dalam_keluarga',
        'pendidikan',
        'pekerjaan',
    ];

    public function dataKeluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'id_keluarga', 'id_keluarga');
    }
}

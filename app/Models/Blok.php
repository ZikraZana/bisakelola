<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blok extends Model
{
    protected $table = 'blok';

    protected $primaryKey = 'id_blok';

    protected $fillable = ['nama_blok'];

    public function dataKeluarga()
    {
        return $this->hasMany(DataKeluarga::class, 'id_blok', 'id_blok');
    }
}

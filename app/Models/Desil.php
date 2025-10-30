<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desil extends Model
{
    protected $table = 'desil';

    protected $primaryKey = 'id_desil';

    protected $fillable = ['tingkat_desil'];

    public function dataKeluarga()
    {
        return $this->hasMany(DataKeluarga::class, 'id_desil', 'id_desil');
    }
}

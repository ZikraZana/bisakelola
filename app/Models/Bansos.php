<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bansos extends Model
{
    use HasFactory;

    protected $table = 'bansos';

    // 1. Beri tahu Eloquent apa Primary Key Anda
    protected $primaryKey = 'id_bansos';

    protected $fillable = [
        'nama_bansos',
        'deskripsi',
    ];

    // 2. Eksplisit tentukan Foreign Key dan Primary Key di relasi
    public function dataPenerimaBansos()
    {
        // (Model, Foreign Key, Local Key/Primary Key)
        return $this->hasMany(DataPenerimaBansos::class, 'id_bansos', 'id_bansos');
    }
}

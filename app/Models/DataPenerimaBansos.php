<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPenerimaBansos extends Model
{
    use HasFactory;

    protected $table = 'data_penerima_bansos';

    // 1. Beri tahu Eloquent apa Primary Key Anda
    protected $primaryKey = 'id_penerima_bansos';

    // 2. Jaga PK ini dari mass assignment
    protected $guarded = ['id_penerima_bansos'];

    // --- DEFINISIKAN RELASI SECARA EKSPLISIT ---

    // Relasi ke DataKeluarga (FK: id_keluarga, PK Parent: id_keluarga)
    public function keluarga()
    {
        return $this->belongsTo(DataKeluarga::class, 'id_keluarga', 'id_keluarga');
    }

    // Relasi ke Bansos (FK: id_bansos, PK Parent: id_bansos)
    public function bansos()
    {
        return $this->belongsTo(Bansos::class, 'id_bansos', 'id_bansos');
    }

    // Relasi ke Admin Pengaju (FK: id_admin_pengaju, PK Parent: id)
    public function adminPengaju()
    {
        // Ganti Admin::class jika nama model Anda berbeda
        return $this->belongsTo(Admin::class, 'id_admin_pengaju', 'id_admin');
    }

    // Relasi ke Admin Penyetuju (FK: id_admin_penyetuju, PK Parent: id)
    public function adminPenyetuju()
    {
        // Ganti Admin::class jika nama model Anda berbeda
        return $this->belongsTo(Admin::class, 'id_admin_penyetuju', 'id_admin');
    }
}

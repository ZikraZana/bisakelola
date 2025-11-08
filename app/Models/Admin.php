<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Tambahkan ini untuk relasi
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $table = 'admins';
    protected $primaryKey = 'id_admin';


    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'no_handphone',
        'role',
        'id_blok', // <-- Diubah ke 'id_blok' agar sesuai migrasi
        'bagian',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tambahan: Definisikan relasi Eloquent
     * Ini adalah praktik yang baik.
     */
    public function blok(): BelongsTo
    {
        // Admin ini 'milik' satu Blok
        // Laravel akan otomatis mencari foreign key 'id_blok'
        return $this->belongsTo(Blok::class, 'id_blok', 'id_blok');
    }
}

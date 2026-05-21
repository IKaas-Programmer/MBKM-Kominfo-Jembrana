<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkTracking extends Model
{
    use HasFactory;

    protected $table = 'link_trackings';

    /**
     * Kolom yang diizinkan untuk diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'kode_unik',
        'user_id',
        'nama_tugas',
        'url_target',
        'url_status',
        'status_verifikasi',
        'file_bukti',
    ];

    /**
     * RELASI: Setiap satu baris data tracking link, mutlak milik satu User (Pegawai).
     * 
     * Hubungan balik (Inverse Relationship) menggunakan belongsTo.
     * Contoh penggunaan nanti di View/Controller: $tracking->user->name atau $tracking->user->nip
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
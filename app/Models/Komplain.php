<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komplain extends Model
{
    protected $fillable = [
        'pelanggan_id',
        'judul',
        'deskripsi',
        'tanggapan_admin',
        'status',
        'foto'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}

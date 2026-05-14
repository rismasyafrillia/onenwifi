<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanBerhenti extends Model
{
    protected $fillable = [
        'pelanggan_id',
        'alasan',
        'status',
        'catatan_admin'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
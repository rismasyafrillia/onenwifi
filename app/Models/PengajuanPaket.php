<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPaket extends Model
{
    protected $fillable = [
        'pelanggan_id',
        'paket_lama_id',
        'paket_baru_id',
        'alasan',
        'status',
        'catatan_admin'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function paketLama()
    {
        return $this->belongsTo(Paket::class, 'paket_lama_id');
    }

    public function paketBaru()
    {
        return $this->belongsTo(Paket::class, 'paket_baru_id');
    }
}
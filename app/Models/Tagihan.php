<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';

    protected $fillable = [
        'pelanggan_id',
        'periode',
        'nominal',
        'status',
        'jatuh_tempo'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'belum bayar')
                    ->where('jatuh_tempo', '<', Carbon::today());
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}

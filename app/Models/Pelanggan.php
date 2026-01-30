<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paket;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan'; 

    protected $fillable = [
    'user_id',
    'nama',
    'alamat',
    'daerah',
    'no_hp',
    'paket_id',
    'status',
    'status_pemasangan',
    'bayar_awal',
    'tanggal_aktif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id', 'id');
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function komplains()
    {
        return $this->hasMany(Komplain::class);
    }
}

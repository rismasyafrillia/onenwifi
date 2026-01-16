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
    'no_hp',
    'paket_id',
    'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }
}

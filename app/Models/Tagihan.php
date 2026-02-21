<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Pelanggan;

class Tagihan extends Model
{
    use HasFactory;

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

    /* ===============================
       UPDATE STATUS MENUNGGAK
       =============================== */
    public static function updateStatusMenunggak()
    {
        self::where('status', 'belum bayar')
            ->whereDate('jatuh_tempo', '<', Carbon::today())
            ->update(['status' => 'menunggak']);
    }

    /* ===============================
       GENERATE TAGIHAN MANUAL
       =============================== */
    public static function generateBulanan($periode)
    {
        $periodeCarbon = Carbon::createFromFormat('m-Y', $periode);
        $jatuhTempo = $periodeCarbon->copy()->startOfMonth()->addDays(19);

        $pelanggans = Pelanggan::where('status', 'aktif')
            ->where('status_pemasangan', 'terpasang')
            ->whereNotNull('paket_id')
            ->with('paket')
            ->get();

        foreach ($pelanggans as $p) {

            if (self::where('pelanggan_id', $p->id)
                ->where('periode', $periode)
                ->exists()) {
                continue;
            }

            self::create([
                'pelanggan_id' => $p->id,
                'periode'      => $periode,
                'nominal'      => $p->paket->harga,
                'jatuh_tempo'  => $jatuhTempo,
                'status'       => 'belum bayar',
            ]);
        }
    }
}

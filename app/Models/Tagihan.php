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
       PROSES OTOMATIS BULANAN
       =============================== */

    public static function prosesOtomatis()
    {
        // lewat jatuh tempo → menunggak
        self::where('status', 'belum bayar')
            ->whereDate('jatuh_tempo', '<', Carbon::today())
            ->update(['status' => 'menunggak']);

        self::generateBulananJikaPerlu();
    }

    private static function generateBulananJikaPerlu()
    {
        $now        = Carbon::now();
        $periode    = $now->format('m-Y');
        $jatuhTempo = $now->copy()->startOfMonth()->addDays(19);

        $pelanggans = Pelanggan::where('status', 'aktif')
            ->where('status_pemasangan', 'terpasang')
            ->whereNotNull('paket_id')
            ->with('paket')
            ->get();

        foreach ($pelanggans as $p) {

            // jangan buat dobel
            if (self::where('pelanggan_id', $p->id)
                ->where('periode', $periode)
                ->exists()) {
                continue;
            }

            // bulan pertama bayar cash → langsung lunas
            if (
                $p->bayar_awal == 1 &&
                $p->tanggal_aktif &&
                Carbon::parse($p->tanggal_aktif)->isSameMonth($now)
            ) {
                self::create([
                    'pelanggan_id' => $p->id,
                    'periode'      => $periode,
                    'nominal'      => $p->paket->harga,
                    'jatuh_tempo'  => $jatuhTempo,
                    'status'       => 'lunas',
                ]);
                continue;
            }

            // normal bulanan (TIDAK GABUNG TUNGGAKAN)
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

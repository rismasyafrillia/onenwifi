<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Pelanggan;

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

    /* ===============================
       RELASI
       =============================== */

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /* ===============================
       OPSI 3 – LOGIC OTOMATIS
       =============================== */

    public static function prosesOtomatis()
    {
        // 1️⃣ Ubah status tagihan lewat jatuh tempo → menunggak
        self::where('status', 'belum bayar')
            ->whereDate('jatuh_tempo', '<', Carbon::today())
            ->update(['status' => 'menunggak']);

        // 2️⃣ Generate tagihan bulan berjalan (jika perlu)
        self::generateBulananJikaPerlu();
    }

    private static function generateBulananJikaPerlu()
    {
        $now        = Carbon::now();
        $periode    = $now->format('m-Y');
        $jatuhTempo = $now->copy()->startOfMonth()->addDays(19); // tgl 20

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

            // ✅ BULAN PERTAMA BAYAR CASH → BUAT TAGIHAN LUNAS
            if (
                $p->bayar_awal == 1 &&
                $p->tanggal_aktif &&
                Carbon::parse($p->tanggal_aktif)->isSameMonth($now)
            ) {
                self::create([
                    'pelanggan_id' => $p->id,
                    'periode'      => $periode,
                    'nominal'      => $p->paket->harga ?? 0,
                    'jatuh_tempo'  => $jatuhTempo,
                    'status'       => 'lunas',
                ]);
                continue;
            }

            // 🔢 Hitung tunggakan lama
            $tunggakan = self::where('pelanggan_id', $p->id)
                ->whereIn('status', ['belum bayar', 'menunggak'])
                ->whereDate('jatuh_tempo', '<', Carbon::today())
                ->sum('nominal');

            $hargaPaket = $p->paket->harga ?? 0;

            // ✅ Buat tagihan baru
            self::create([
                'pelanggan_id' => $p->id,
                'periode'      => $periode,
                'nominal'      => $hargaPaket + $tunggakan,
                'jatuh_tempo'  => $jatuhTempo,
                'status'       => 'belum bayar',
            ]);
        }
    }
}

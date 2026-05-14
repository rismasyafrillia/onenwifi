<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_pakets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pelanggan_id')
                ->constrained('pelanggan')
                ->onDelete('cascade');

            $table->foreignId('paket_lama_id')
                ->constrained('pakets')
                ->onDelete('cascade');

            $table->foreignId('paket_baru_id')
                ->constrained('pakets')
                ->onDelete('cascade');

            $table->text('alasan');

            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('menunggu');

            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pakets');
    }
};

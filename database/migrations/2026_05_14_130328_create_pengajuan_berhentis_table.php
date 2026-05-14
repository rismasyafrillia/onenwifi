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
        Schema::create('pengajuan_berhentis', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('pelanggan_id');

            $table->foreign('pelanggan_id')
                  ->references('id')
                  ->on('pelanggan')
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
        Schema::dropIfExists('pengajuan_berhentis');
    }
};
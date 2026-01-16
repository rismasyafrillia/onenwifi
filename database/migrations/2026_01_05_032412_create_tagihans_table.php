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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelanggan_id');
            $table->string('periode');
            $table->integer('nominal');
            $table->enum('status', ['belum bayar', 'lunas', 'menunggak'])->default('belum bayar');
            $table->date('jatuh_tempo')->nullable();
            $table->timestamps();

            $table->foreign('pelanggan_id')
                  ->references('id')->on('pelanggan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};

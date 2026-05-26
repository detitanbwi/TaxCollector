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
        Schema::create('pajak_tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('nopol')->unique();
            $table->string('nama_pemilik');
            $table->string('jenis_kendaraan')->nullable();
            $table->string('merek_nama')->nullable();
            $table->string('merek_type')->nullable();
            $table->integer('th_buat')->nullable();
            $table->bigInteger('pkb')->default(0);
            $table->bigInteger('opsen')->default(0);
            $table->bigInteger('nominal')->default(0); // PKB + OPSEN
            $table->string('masa_laku')->nullable();
            $table->string('masa_stnk')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->boolean('is_ditagih')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pajak_tagihans');
    }
};

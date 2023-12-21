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
        Schema::create('purposes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penanggung_jawab_id');
            $table->string('nama_pemohon')->nullable();
            $table->string('proses_permohonan')->nullable();
            $table->string('no_akta')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('jenis_pekerjaan')->enum(['PPAT', "NOTARIS"]);
            $table->string('proses_sertifikat')->default('masuk');
            $table->text('keterangan')->nullable();
            $table->text('tanggal');
            $table->timestamps();
            $table->foreign('penanggung_jawab_id')->references('id')->on('penanggung_jawab')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purposes');
    }
};

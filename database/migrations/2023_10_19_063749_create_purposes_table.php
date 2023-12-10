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
            $table->string('nama_pemohon');
            $table->string('domisili');
            $table->string('nomor_sertifikat');
            $table->string('desa');
            $table->string('no_berkas');
            $table->string('proses_sertifikat')->default('masuk');
            $table->string('document');
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

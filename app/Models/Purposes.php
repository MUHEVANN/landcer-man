<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purposes extends Model
{
    use HasFactory;
    protected $table = 'purposes';
    protected $fillable = [
        'nama_pemohon',
        'penanggung_jawab_id',
        'domisili',
        'nomor_sertifikat',
        'no_berkas',
        'proses_sertifikat',
        'document',
        'desa'
    ];

    public function penanggung_jawab()
    {
        return $this->belongsTo(PenanggungJawab::class, 'penanggung_jawab_id', 'id');
    }
}

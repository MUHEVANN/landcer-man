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
        'no_akta',
        'bank_name',
        'proses_permohonan',
        'jenis_pekerjaan',
        'keterangan',
        'proses_sertifikat',

    ];

    public function penanggung_jawab()
    {
        return $this->belongsTo(PenanggungJawab::class, 'penanggung_jawab_id', 'id');
    }

    public function document()
    {
        return $this->hasMany(Document::class, 'purpose_id', 'id');
    }
}

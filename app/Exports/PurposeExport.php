<?php

namespace App\Exports;

use App\Models\Purposes;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurposeExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    private $no;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Purposes::with('penanggung_jawab')->get();
    }

    public function map($purpose): array
    {
        $this->no++;
        return [
            $this->no,
            $purpose->created_at->format('d F Y'),
            $purpose->jenis_pekerjaan,
            $purpose->no_akta,
            $purpose->proses_permohonan,
            $purpose->bank_name,
            $purpose->nama_pemohon,
            $purpose->keterangan,
            $purpose->penanggung_jawab->nama_penanggung_jawab,
            $purpose->proses_sertifikat
        ];
    }

    public function headings(): array
    {
        return [
            "#",
            "Tanggal Dibuat",
            "Jenis Pekerjaan",
            "Nomor Akta",
            "Proses Permohonan",
            "Nama Bank",
            "Nama Pemohon",
            "Keterangan",
            "Penanggung Jawab",
            "Status"
        ];
    }
}

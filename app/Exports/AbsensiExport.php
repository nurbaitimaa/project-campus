<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Absensi::with(['salesMarketing', 'updatedBy'])->latest('tanggal');

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('tanggal', [$this->request->start_date, $this->request->end_date]);
        }

        if ($this->request->filled('sales_id')) {
            $query->where('sales_marketing_id', $this->request->sales_id);
        }

        return $query->get();
    }

    /**
     * @var Absensi $absensi
     */
    public function map($absensi): array
    {
        return [
            \Carbon\Carbon::parse($absensi->tanggal)->format('d-m-Y'),
            $absensi->salesMarketing->nama_sales ?? '-',
            $absensi->status,
            $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-',
            $absensi->jam_keluar ? \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') : '-',
            $absensi->keterangan ?? '-',
            $absensi->updatedBy->name ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Sales',
            'Status',
            'Jam Masuk',
            'Jam Keluar',
            'Keterangan',
            'Diedit Oleh',
        ];
    }
}

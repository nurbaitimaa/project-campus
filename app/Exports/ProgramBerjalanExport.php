<?php

namespace App\Exports;

use App\Models\ProgramBerjalan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class ProgramBerjalanExport implements FromCollection, WithHeadings, WithMapping
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
        $query = ProgramBerjalan::with(['customer', 'program'])->latest('tanggal');

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('start_date', [$this->request->start_date, $this->request->end_date]);
        }
        if ($this->request->filled('customer_id')) {
            $query->where('kode_customer', $this->request->customer_id);
        }
        if ($this->request->filled('program_id')) {
            $query->where('kode_program', $this->request->program_id);
        }

        return $query->get();
    }

    /**
     * @var ProgramBerjalan $program
     */
    public function map($program): array
    {
        return [
            $program->customer->nama_customer ?? '-',
            $program->program->nama_program ?? '-',
            \Carbon\Carbon::parse($program->start_date)->format('d-m-Y'),
            \Carbon\Carbon::parse($program->end_date)->format('d-m-Y'),
            $program->target,
            $program->min_pembelian,
            $program->reward,
            $program->reward_type,
            $program->pic,
        ];
    }

    public function headings(): array
    {
        return [
            'Customer',
            'Nama Program',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Deskripsi Hadiah',
            'Minimal Pembelian',
            'Nilai Reward',
            'Tipe Reward',
            'PIC',
        ];
    }
}

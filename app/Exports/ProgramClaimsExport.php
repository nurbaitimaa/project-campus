<?php

namespace App\Exports;

use App\Models\ProgramClaim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class ProgramClaimsExport implements FromCollection, WithHeadings, WithMapping
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
        $query = ProgramClaim::with(['programBerjalan.customer', 'programBerjalan.program']);

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('tanggal_klaim', [$this->request->start_date, $this->request->end_date]);
        }
        if ($this->request->filled('customer_id')) {
            $query->whereHas('programBerjalan', function ($q) {
                $q->where('kode_customer', $this->request->customer_id);
            });
        }
        if ($this->request->filled('program_id')) {
            $query->whereHas('programBerjalan', function ($q) {
                $q->where('kode_program', $this->request->program_id);
            });
        }
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        return $query->latest('tanggal_klaim')->get();
    }

    /**
     * @var ProgramClaim $claim
     */
    public function map($claim): array
    {
        return [
            $claim->kode_transaksi,
            \Carbon\Carbon::parse($claim->tanggal_klaim)->format('d-m-Y'),
            $claim->programBerjalan->customer->nama_customer ?? '-',
            $claim->programBerjalan->program->nama_program ?? '-',
            $claim->total_pembelian,
            $claim->total_klaim,
            ucfirst($claim->status),
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal Klaim',
            'Customer',
            'Program',
            'Total Pembelian',
            'Total Klaim',
            'Status',
        ];
    }
}

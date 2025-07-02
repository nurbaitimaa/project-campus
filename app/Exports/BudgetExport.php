<?php

namespace App\Exports;

use App\Models\MarketingBudget;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class BudgetExport implements FromCollection, WithHeadings, WithMapping
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
        $query = MarketingBudget::with('customer');

        if ($this->request->filled('customer_id')) {
            $query->where('customer_id', $this->request->customer_id);
        }
        if ($this->request->filled('tahun_anggaran')) {
            $query->where('tahun_anggaran', $this->request->tahun_anggaran);
        }

        $budgets = $query->get();

        // Hitung penggunaan untuk setiap budget
        return $budgets->map(function ($budget) {
            $totalTerpakai = \App\Models\ProgramClaim::where('status', 'approved')
                ->whereHas('programBerjalan', function($q) use ($budget) {
                    $q->where('kode_customer', $budget->customer->kode_customer);
                })
                ->whereYear('tanggal_klaim', $budget->tahun_anggaran)
                ->sum('total_klaim');

            $budget->terpakai = $totalTerpakai;
            $budget->sisa_aktual = $budget->nilai_budget - $totalTerpakai;
            return $budget;
        });
    }

    /**
     * @var MarketingBudget $budget
     */
    public function map($budget): array
    {
        return [
            $budget->customer->nama_customer,
            $budget->tahun_anggaran,
            $budget->nilai_budget,
            $budget->terpakai,
            $budget->sisa_aktual,
        ];
    }

    public function headings(): array
    {
        return [
            'Customer',
            'Tahun Anggaran',
            'Budget Awal',
            'Terpakai (Klaim Approved)',
            'Sisa Budget',
        ];
    }
}

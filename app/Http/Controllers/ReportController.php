<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Inventory;
use App\Models\ProgramClaim;
use App\Models\MarketingBudget;
use App\Models\SalesMarketing;
use App\Models\Customer;
use App\Models\Program;
use App\Models\InventoryTransaction;
use App\Models\ProgramBerjalan;
use App\Exports\BudgetExport;
use App\Exports\AbsensiExport;
use App\Exports\ProgramBerjalanExport;
use App\Exports\ProgramClaimsExport;
use Maatwebsite\Excel\Facades\Excel; // Pastikan ini juga di-import

class ReportController extends Controller
{
    // ... (fungsi index, absensi, inventory, klaim, program, budget tetap sama) ...
    public function index()
    {
        return view('reports.index');
    }

    public function absensi(Request $request)
    {
        $sales = SalesMarketing::orderBy('nama_sales')->get();
        $query = Absensi::with(['salesMarketing', 'updatedBy'])->latest('tanggal');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('sales_id')) {
            $query->where('sales_marketing_id', $request->sales_id);
        }
        $absensi = $query->get();

        $rekapitulasi = [
            'Hadir' => $absensi->where('status', 'Hadir')->count(),
            'Izin' => $absensi->where('status', 'Izin')->count(),
            'Sakit' => $absensi->where('status', 'Sakit')->count(),
            'Alfa' => $absensi->where('status', 'Alfa')->count(),
            'Total' => $absensi->count()
        ];

        return view('reports.absensi', [
            'absensiList' => $absensi,
            'sales' => $sales,
            'rekapitulasi' => $rekapitulasi,
            'request' => $request
        ]);
    }

    public function inventory(Request $request)
    {
        $inventoryItems = Inventory::orderBy('nama_barang')->get();
        $query = Inventory::with('transactions');

        if ($request->filled('inventory_id')) {
            $query->where('id', $request->inventory_id);
        }

        $inventories = $query->get();

        $reportData = $inventories->map(function ($item) use ($request) {
            $transactions = $item->transactions();
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $transactions->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
            }
            $filteredTransactions = $transactions->get();

            $totalMasuk = $filteredTransactions->where('tipe', 'masuk')->sum('jumlah');
            $totalKeluar = $filteredTransactions->where('tipe', 'keluar')->sum('jumlah');
            
            $stokAkhir = $item->stok_awal + $totalMasuk - $totalKeluar;

            return [
                'nama_barang' => $item->nama_barang,
                'satuan' => $item->satuan,
                'stok_awal' => $item->stok_awal,
                'total_masuk' => $totalMasuk,
                'total_keluar' => $totalKeluar,
                'stok_akhir' => $stokAkhir
            ];
        });

        return view('reports.inventory', [
            'reportData' => $reportData,
            'inventoryItems' => $inventoryItems,
            'request' => $request
        ]);
    }
    
    public function klaim(Request $request)
    {
        $customers = Customer::orderBy('nama_customer')->get();
        $programs = Program::orderBy('nama_program')->get();
        $statuses = ['pending', 'approved', 'rejected'];

        $query = ProgramClaim::with(['programBerjalan.customer', 'programBerjalan.program']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_klaim', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('customer_id')) {
            $query->whereHas('programBerjalan', function ($q) use ($request) {
                $q->where('kode_customer', $request->customer_id);
            });
        }
        if ($request->filled('program_id')) {
            $query->whereHas('programBerjalan', function ($q) use ($request) {
                $q->where('kode_program', $request->program_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $claims = $query->latest('tanggal_klaim')->get();

        $rekapitulasi = [
            'total_penjualan' => $claims->sum('total_pembelian'),
            'total_klaim' => $claims->sum('total_klaim'),
            'total_data' => $claims->count(),
            'pending' => $claims->where('status', 'pending')->count(),
            'approved' => $claims->where('status', 'approved')->count(),
            'rejected' => $claims->where('status', 'rejected')->count(),
        ];

        return view('reports.klaim', [
            'claims' => $claims,
            'customers' => $customers,
            'programs' => $programs,
            'statuses' => $statuses,
            'rekapitulasi' => $rekapitulasi,
            'request' => $request
        ]);
    }

    public function program(Request $request)
    {
        $customers = Customer::orderBy('nama_customer')->get();
        $programs = Program::orderBy('nama_program')->get();

        $query = ProgramBerjalan::with(['customer', 'program'])->latest('tanggal');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('customer_id')) {
            $query->where('kode_customer', $request->customer_id);
        }
        if ($request->filled('program_id')) {
            $query->where('kode_program', $request->program_id);
        }

        $programBerjalan = $query->get();

        return view('reports.program', [
            'programBerjalan' => $programBerjalan,
            'customers' => $customers,
            'programs' => $programs,
            'request' => $request
        ]);
    }
    
    public function budget(Request $request)
    {
        $customers = Customer::orderBy('nama_customer')->get();
        $years = MarketingBudget::selectRaw('DISTINCT tahun_anggaran')->orderBy('tahun_anggaran', 'desc')->pluck('tahun_anggaran');

        $query = MarketingBudget::with('customer');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('tahun_anggaran')) {
            $query->where('tahun_anggaran', $request->tahun_anggaran);
        }

        $budgets = $query->get();

        $reportData = $budgets->map(function ($budget) {
            $totalTerpakai = ProgramClaim::where('status', 'approved')
                ->whereHas('programBerjalan', function($q) use ($budget) {
                    $q->where('kode_customer', $budget->customer->kode_customer);
                })
                ->whereYear('tanggal_klaim', $budget->tahun_anggaran)
                ->sum('total_klaim');

            $budget->terpakai = $totalTerpakai;
            $budget->sisa_aktual = $budget->nilai_budget - $totalTerpakai;
            return $budget;
        });

        return view('reports.budget', [
            'reportData' => $reportData,
            'customers' => $customers,
            'years' => $years,
            'request' => $request,
        ]);
    }

    public function exportBudgetExcel(Request $request)
    {
        return Excel::download(new BudgetExport($request), 'laporan-budget-'.date('Y-m-d').'.xlsx');
    }

    public function exportAbsensiExcel(Request $request)
    {
        return Excel::download(new AbsensiExport($request), 'laporan-absensi-'.date('Y-m-d').'.xlsx');
    }

    public function exportProgramExcel(Request $request)
    {
        return Excel::download(new ProgramBerjalanExport($request), 'laporan-program-berjalan-'.date('Y-m-d').'.xlsx');
    }
    
    public function exportKlaimExcel(Request $request)
    {
        return Excel::download(new ProgramClaimsExport($request), 'laporan-program-berjalan-'.date('Y-m-d').'.xlsx');
    }

}

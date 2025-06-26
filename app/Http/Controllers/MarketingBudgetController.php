<?php

namespace App\Http\Controllers;

use App\Models\MarketingBudget;
use App\Models\Customer;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingBudgetController extends Controller
{
    // Semua user bisa melihat data
    public function index()
    {
        $budgets = MarketingBudget::with(['customer', 'program'])->latest()->paginate(10);
        return view('marketing_budgets.index', compact('budgets'));
    }

    // Hanya manager yang boleh akses create
    public function create()
    {
        if (auth()->user()->role !== 'manager') {
            abort(403, 'Akses hanya untuk Manager');
        }

        $customers = Customer::all();
        $programs = Program::all();
        return view('marketing_budgets.create', compact('customers', 'programs'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'manager') {
            abort(403, 'Akses hanya untuk Manager');
        }

        $request->validate([
    'customer_id' => 'required|exists:customers,id',
    'tahun_anggaran' => 'required|digits:4',
    'nilai_budget' => 'required|numeric|min:0',
]);

MarketingBudget::create([
    'customer_id' => $request->customer_id,
    'tahun_anggaran' => $request->tahun_anggaran,
    'nilai_budget' => $request->nilai_budget,
    'sisa_budget' => $request->nilai_budget,
    'created_by' => auth()->id(),
]);


        return redirect()->route('marketing-budgets.index')->with('success', 'Budget berhasil ditambahkan');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProgramBerjalan;
use App\Models\Program;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramBerjalanController extends Controller
{
    public function index(Request $request)
{
    $query = ProgramBerjalan::with(['customer', 'program'])->latest();

    // Filter berdasarkan customer
    if ($request->filled('customer')) {
        $query->where('kode_customer', $request->customer);
    }

    // Filter berdasarkan program
    if ($request->filled('program')) {
        $query->where('kode_program', $request->program);
    }

    $programs = $query->get();
    $customers = Customer::all();
    $programList = Program::all();

    return view('program_berjalan.index', compact('programs', 'customers', 'programList'));
}


    public function create()
    {
        $customers = Customer::all();
        $programs = Program::all();
        return view('program_berjalan.create', compact('customers', 'programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode_customer' => 'required|exists:customers,kode_customer',
            'kode_program' => 'required|exists:programs,kode_program',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('program_files', 'public');
        }

        ProgramBerjalan::create($data);

        return redirect()->route('program-berjalan.index')->with('success', 'Program berhasil ditambahkan');
    }
}

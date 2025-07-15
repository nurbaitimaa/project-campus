<?php

namespace App\Http\Controllers;

use App\Models\ProgramBerjalan;
use App\Models\Customer;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramBerjalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = ProgramBerjalan::with(['customer', 'program'])->latest('tanggal');

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%");
            })->orWhereHas('program', function ($q) use ($search) {
                $q->where('nama_program', 'like', "%{$search}%");
            });
        }

        // Di sini kita mendefinisikan variabel $programBerjalans
        $programBerjalans = $query->paginate(10);

        // Dan di sini kita mengirimkannya ke view
        return view('program_berjalan.index', compact('programBerjalans', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $programs = Program::all();
        return view('program_berjalan.create', compact('customers', 'programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode_customer' => 'required|exists:customers,kode_customer',
            'kode_program' => 'required|exists:programs,kode_program',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'nullable|string',
            'pic' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'min_pembelian' => 'nullable|numeric',
            'reward' => 'nullable|numeric',
            'reward_type' => 'nullable|in:unit,rupiah,persen',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $data = $request->except('file_path');
        $data['created_by'] = auth()->id();

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('program_files', 'public');
        }

        ProgramBerjalan::create($data);

        return redirect()->route('program-berjalan.index')->with('success', 'Program Berjalan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramBerjalan $program_berjalan)
    {
        $customers = Customer::all();
        $programs = Program::all();
        return view('program_berjalan.edit', [
            'program' => $program_berjalan,
            'customers' => $customers,
            'programs' => $programs
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramBerjalan $program_berjalan)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode_customer' => 'required|exists:customers,kode_customer',
            'kode_program' => 'required|exists:programs,kode_program',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'target' => 'nullable|string',
            'pic' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'min_pembelian' => 'nullable|numeric',
            'reward' => 'nullable|numeric',
            'reward_type' => 'nullable|in:unit,rupiah,persen',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        $data = $request->except('file_path');

        if ($request->hasFile('file_path')) {
            if ($program_berjalan->file_path && Storage::disk('public')->exists($program_berjalan->file_path)) {
                Storage::disk('public')->delete($program_berjalan->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('program_files', 'public');
        }

        $program_berjalan->update($data);

        return redirect()->route('program-berjalan.index')->with('success', 'Data Program Berjalan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramBerjalan $program_berjalan)
    {
        if ($program_berjalan->file_path && Storage::disk('public')->exists($program_berjalan->file_path)) {
            Storage::disk('public')->delete($program_berjalan->file_path);
        }
        $program_berjalan->delete();
        return redirect()->route('program-berjalan.index')->with('success', 'Data Program Berjalan berhasil dihapus.');
    }
}

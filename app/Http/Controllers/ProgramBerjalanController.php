<?php

namespace App\Http\Controllers;

use App\Models\ProgramBerjalan;
use App\Models\Customer;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramBerjalanController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramBerjalan::with(['program', 'customer'])->latest();

        if ($request->filled('customer')) {
            $query->where('kode_customer', $request->customer);
        }

        if ($request->filled('program')) {
            $query->where('kode_program', $request->program);
        }

        $programs = $query->paginate(10);
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
            'tanggal'              => 'required|date',
            'kode_customer'        => 'required|exists:customers,kode_customer',
            'kode_program'         => 'required|exists:programs,kode_program',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after_or_equal:start_date',
            'target'               => 'nullable|string',
            'pic'                  => 'nullable|string',
            'keterangan'           => 'nullable|string',
            'budget'               => 'nullable|numeric',
            'file_path'            => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'nilai_klaim_per_item' => 'nullable|numeric',
            'persen_klaim'         => 'nullable|numeric',
            'nominal_klaim'        => 'nullable|numeric',
        ]);

        $data = $request->only([
            'tanggal', 'kode_customer', 'kode_program', 'start_date', 'end_date',
            'target', 'pic', 'keterangan', 'budget',
            'nilai_klaim_per_item', 'persen_klaim', 'nominal_klaim',
        ]);

        $data['created_by'] = auth()->id();

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('program_files', 'public');
        }

        ProgramBerjalan::create($data);

        return redirect()->route('program-berjalan.index')->with('success', 'Program Berjalan berhasil ditambahkan.');
    }

    public function edit(ProgramBerjalan $program_berjalan)
    {
        $customers = Customer::all();
        $programs = Program::all();

        // Rename variabel agar tetap bisa digunakan sebagai $program di Blade
        return view('program_berjalan.edit', [
            'program' => $program_berjalan,
            'customers' => $customers,
            'programs' => $programs
        ]);
    }

    public function update(Request $request, ProgramBerjalan $program_berjalan)
    {
        $request->validate([
            'tanggal'              => 'required|date',
            'kode_customer'        => 'required|exists:customers,kode_customer',
            'kode_program'         => 'required|exists:programs,kode_program',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after_or_equal:start_date',
            'target'               => 'nullable|string',
            'pic'                  => 'nullable|string',
            'keterangan'           => 'nullable|string',
            'budget'               => 'nullable|numeric',
            'file_path'            => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'nilai_klaim_per_item' => 'nullable|numeric',
            'persen_klaim'         => 'nullable|numeric',
            'nominal_klaim'        => 'nullable|numeric',
        ]);

        $data = $request->only([
            'tanggal', 'kode_customer', 'kode_program', 'start_date', 'end_date',
            'target', 'pic', 'keterangan', 'budget',
            'nilai_klaim_per_item', 'persen_klaim', 'nominal_klaim',
        ]);

        if ($request->hasFile('file_path')) {
            if ($program_berjalan->file_path) {
                Storage::disk('public')->delete($program_berjalan->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('program_files', 'public');
        }

        $program_berjalan->update($data);

        return redirect()->route('program-berjalan.index')->with('success', 'Data Program Berjalan berhasil diperbarui.');
    }

    public function destroy(ProgramBerjalan $program_berjalan)
    {
        if ($program_berjalan->file_path) {
            Storage::disk('public')->delete($program_berjalan->file_path);
        }

        $program_berjalan->delete();

        return redirect()->route('program-berjalan.index')->with('success', 'Data Program Berjalan berhasil dihapus.');
    }

    public function getProgramDetail($kode_program)
    {
        $program = Program::where('kode_program', $kode_program)->firstOrFail();

        return response()->json([
            'parameter_klaim' => $program->parameter_klaim,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProgramBerjalan;
use App\Models\Program;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgramBerjalanController extends Controller
{
    public function index(Request $request)
    {
        $query = ProgramBerjalan::with(['customer', 'program'])->latest();

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
            'tanggal' => 'required|date',
            'kode_customer' => 'required|exists:customers,kode_customer',
            'kode_program' => 'required|exists:programs,kode_program',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $program = Program::where('kode_program', $request->kode_program)->first();

        $data = $request->only([
            'tanggal', 'kode_customer', 'kode_program',
            'start_date', 'end_date', 'target', 'pic',
            'keterangan', 'budget'
        ]);
        $data['created_by'] = Auth::id();

        // Simpan nilai klaim sesuai parameter program
        if ($program) {
            switch ($program->parameter_klaim) {
                case 'nilai_klaim_per_item':
                    $data['nilai_klaim_per_item'] = $request->input('nilai_klaim_per_item');
                    break;
                case 'persen_klaim':
                    $data['persen_klaim'] = $request->input('persen_klaim');
                    break;
                case 'nominal_klaim':
                    $data['nominal_klaim'] = $request->input('nominal_klaim');
                    break;
            }
        }

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('program_files', 'public');
        }

        ProgramBerjalan::create($data);

        return redirect()->route('program-berjalan.index')->with('success', 'Program berhasil ditambahkan');
    }

    public function edit($id)
    {
        $program = ProgramBerjalan::findOrFail($id);
        $customers = Customer::all();
        $programs = Program::all();

        return view('program_berjalan.edit', compact('program', 'customers', 'programs'));
    }

    public function update(Request $request, $id)
    {
        $programBerjalan = ProgramBerjalan::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'kode_customer' => 'required|exists:customers,kode_customer',
            'kode_program' => 'required|exists:programs,kode_program',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $program = Program::where('kode_program', $request->kode_program)->first();

        $data = $request->only([
            'tanggal', 'kode_customer', 'kode_program',
            'start_date', 'end_date', 'target', 'pic',
            'keterangan', 'budget'
        ]);

        // Reset semua nilai klaim
        $data['nilai_klaim_per_item'] = null;
        $data['persen_klaim'] = null;
        $data['nominal_klaim'] = null;

        if ($program) {
            switch ($program->parameter_klaim) {
                case 'nilai_klaim_per_item':
                    $data['nilai_klaim_per_item'] = $request->input('nilai_klaim_per_item');
                    break;
                case 'persen_klaim':
                    $data['persen_klaim'] = $request->input('persen_klaim');
                    break;
                case 'nominal_klaim':
                    $data['nominal_klaim'] = $request->input('nominal_klaim');
                    break;
            }
        }

        if ($request->hasFile('file_path')) {
            if ($programBerjalan->file_path && Storage::disk('public')->exists($programBerjalan->file_path)) {
                Storage::disk('public')->delete($programBerjalan->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('program_files', 'public');
        }

        $programBerjalan->update($data);

        return redirect()->route('program-berjalan.index')->with('success', 'Program berhasil diperbarui');
    }

    public function destroy($id)
    {
        $program = ProgramBerjalan::findOrFail($id);

        if ($program->file_path && Storage::disk('public')->exists($program->file_path)) {
            Storage::disk('public')->delete($program->file_path);
        }

        $program->delete();

        return redirect()->route('program-berjalan.index')->with('success', 'Program berhasil dihapus');
    }

    public function getDetail($id)
    {
        $programBerjalan = ProgramBerjalan::with(['customer', 'program'])->findOrFail($id);

        return response()->json([
            'nama_customer' => $programBerjalan->customer->nama_customer ?? '',
            'jenis_program' => $programBerjalan->program->jenis_program ?? '',
            'parameter_klaim' => $programBerjalan->program->parameter_klaim ?? '',
        ]);
    }

    public function getProgramDetail($kode_program)
{
    $program = Program::where('kode_program', $kode_program)->first();

    if (!$program) {
        return response()->json(['message' => 'Program tidak ditemukan'], 404);
    }

    return response()->json([
        'jenis_program' => $program->jenis_program,
        'parameter_klaim' => $program->parameter_klaim,
    ]);
}

}

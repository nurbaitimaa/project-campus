<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_program'     => 'required|unique:programs,kode_program',
            'nama_program'     => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'jenis_program'    => 'nullable|string|max:255',
            'parameter_klaim'  => 'nullable|string|max:255',
            'tipe_klaim'       => 'required|string|in:rupiah,unit,persen', // validasi tambahan
        ]);

        Program::create([
            'kode_program'     => $request->kode_program,
            'nama_program'     => $request->nama_program,
            'deskripsi'        => $request->deskripsi,
            'jenis_program'    => $request->jenis_program,
            'parameter_klaim'  => $request->parameter_klaim,
            'tipe_klaim'       => $request->tipe_klaim, // field baru
        ]);

        return redirect()->route('programs.index')
                         ->with('success', 'Data program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'kode_program'     => 'required|string|max:100|unique:programs,kode_program,' . $program->id,
            'nama_program'     => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'jenis_program'    => 'nullable|string|max:255',
            'parameter_klaim'  => 'nullable|string|max:255',
            'tipe_klaim'       => 'required|string|in:rupiah,unit,persen', // validasi tambahan
        ]);

        $program->update([
            'kode_program'     => $request->kode_program,
            'nama_program'     => $request->nama_program,
            'deskripsi'        => $request->deskripsi,
            'jenis_program'    => $request->jenis_program,
            'parameter_klaim'  => $request->parameter_klaim,
            'tipe_klaim'       => $request->tipe_klaim, // field baru
        ]);

        return redirect()->route('programs.index')
                         ->with('success', 'Data program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('programs.index')
                         ->with('success', 'Data program berhasil dihapus.');
    }
}

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
            'kode_program' => 'required|unique:programs',
            'nama_program' => 'required',
            'deskripsi'    => 'nullable',
        ]);

        Program::create($request->all());
        return redirect()->route('programs.index')->with('success', 'Data program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'kode_program' => 'required|unique:programs,kode_program,' . $program->id,
            'nama_program' => 'required',
            'deskripsi'    => 'nullable'
        ]);

        $program->update($request->all());
        return redirect()->route('programs.index')->with('success', 'Data program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('programs.index')->with('success', 'Data program berhasil dihapus.');
    }
}

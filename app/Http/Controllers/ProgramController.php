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
            'jenis_program'    => 'required|in:diskon,bundling,target_penjualan',
            'parameter_klaim'  => 'required|in:per_item,persen,nominal',
            'tipe_klaim'       => 'required|in:rupiah,unit,persen',
            'min_pembelian'    => 'nullable|numeric',
            'reward'           => 'nullable|numeric',
            'reward_type'      => 'nullable|in:unit,rupiah,persen',
        ]);

        Program::create($request->all());

        return redirect()->route('programs.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'kode_program'     => 'required|unique:programs,kode_program,' . $program->id,
            'nama_program'     => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'jenis_program'    => 'required|in:diskon,bundling,target_penjualan',
            'parameter_klaim'  => 'required|in:per_item,persen,nominal',
            'tipe_klaim'       => 'required|in:rupiah,unit,persen',
            'min_pembelian'    => 'nullable|numeric',
            'reward'           => 'nullable|numeric',
            'reward_type'      => 'nullable|in:unit,rupiah,persen',
        ]);

        $program->update($request->all());

        return redirect()->route('programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('programs.index')->with('success', 'Program berhasil dihapus.');
    }
}

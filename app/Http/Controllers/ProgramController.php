<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Ambil kata kunci pencarian dari request
        $search = $request->input('search');

        // Mulai query ke model Program
        $query = Program::query();

        // Jika ada kata kunci pencarian, terapkan filter
        if ($search) {
            $query->where('nama_program', 'like', "%{$search}%")
                  ->orWhere('kode_program', 'like', "%{$search}%");
        }

        // Ambil hasil dengan paginasi agar lebih efisien
        $programs = $query->latest()->paginate(10);

        // Kirim data dan kata kunci pencarian ke view
        return view('programs.index', compact('programs', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('programs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_program'    => 'required|unique:programs,kode_program',
            'nama_program'    => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'jenis_program'   => 'required|in:diskon,bundling,target_penjualan',
            'parameter_klaim' => 'required|in:per_item,persen,nominal',
            'tipe_klaim'      => 'required|in:rupiah,unit,persen',
        ]);

        Program::create($request->all());

        return redirect()->route('programs.index')->with('success', 'Program berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        return view('programs.edit', compact('program'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        $request->validate([
            'kode_program'    => 'required|unique:programs,kode_program,' . $program->id,
            'nama_program'    => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'jenis_program'   => 'required|in:diskon,bundling,target_penjualan',
            'parameter_klaim' => 'required|in:per_item,persen,nominal',
            'tipe_klaim'      => 'required|in:rupiah,unit,persen',
        ]);

        $program->update($request->all());

        return redirect()->route('programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('programs.index')->with('success', 'Program berhasil dihapus.');
    }
}

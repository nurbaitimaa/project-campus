<?php

namespace App\Http\Controllers;

use App\Models\SalesMarketing;
use Illuminate\Http\Request;

class SalesMarketingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = SalesMarketing::query();

        if ($search) {
            $query->where('nama_sales', 'like', "%{$search}%")
                  ->orWhere('kode_sales', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
        }

        // Menggunakan paginate untuk membatasi data per halaman
        $salesMarketings = $query->latest()->paginate(10);

        // Mengganti variabel 'sales' menjadi 'salesMarketings' agar sesuai
        return view('sales-marketing.index', [
            'sales' => $salesMarketings,
            'search' => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sales-marketing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Menggunakan validasi asli Anda
        $request->validate([
            'kode_sales' => 'required|unique:sales_marketings',
            'nama_sales' => 'required|string|max:255',
            'email'      => 'required|email|unique:sales_marketings,email',
            'telepon'    => 'required|string|max:15',
        ]);

        SalesMarketing::create($request->all());

        return redirect()->route('sales-marketing.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $salesMarketing = SalesMarketing::findOrFail($id);
        return view('sales-marketing.edit', compact('salesMarketing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $salesMarketing = SalesMarketing::findOrFail($id);

        // Menggunakan validasi asli Anda
        $request->validate([
            'kode_sales' => 'required|unique:sales_marketings,kode_sales,' . $salesMarketing->id,
            'nama_sales' => 'required|string|max:255',
            'email'      => 'required|email|unique:sales_marketings,email,' . $salesMarketing->id,
            'telepon'    => 'required|string|max:15',
        ]);

        $salesMarketing->update($request->all());

        return redirect()->route('sales-marketing.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $salesMarketing = SalesMarketing::findOrFail($id);
        $salesMarketing->delete();

        return redirect()->route('sales-marketing.index')->with('success', 'Data berhasil dihapus!');
    }
}

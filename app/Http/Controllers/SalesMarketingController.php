<?php

namespace App\Http\Controllers;

use App\Models\SalesMarketing;
use Illuminate\Http\Request;

class SalesMarketingController extends Controller
{
    public function index()
    {
        $sales = SalesMarketing::all();
        return view('sales-marketing.index', compact('sales'));
    }

    public function create()
    {
        return view('sales-marketing.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_sales' => 'required|unique:sales_marketings',
            'nama_sales' => 'required',
            'email'      => 'required|email',
            'telepon'    => 'required'
        ]);

        SalesMarketing::create($request->all());
        return redirect()->route('sales-marketing.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $salesMarketing = SalesMarketing::findOrFail($id);
        return view('sales-marketing.edit', compact('salesMarketing'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_sales' => 'required',
            'nama_sales' => 'required',
            'email'      => 'required|email',
            'telepon'    => 'required'
        ]);

        $salesMarketing = SalesMarketing::findOrFail($id);
        $salesMarketing->update($request->all());

        return redirect()->route('sales-marketing.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $salesMarketing = SalesMarketing::findOrFail($id);
        $salesMarketing->delete();

        return redirect()->route('sales-marketing.index')->with('success', 'Data berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::all();
        return view('inventory.index', compact('inventories'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok_awal'   => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Data inventory berhasil ditambahkan.');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok_awal'   => 'required|integer|min:0',
            'satuan'      => 'required|string|max:50',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventory.index')->with('success', 'Data inventory berhasil diperbarui.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Data inventory berhasil dihapus.');
    }
}

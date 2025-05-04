<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\InventoryTransaction;

class InventoryTransactionController extends Controller
{
    /**
     * Menampilkan daftar transaksi inventory.
     */
    public function index($inventoryId = null)
{
    if ($inventoryId) {
        // Mode: Tampilkan transaksi untuk 1 barang
        $inventory = Inventory::findOrFail($inventoryId);
        $transactions = InventoryTransaction::where('inventory_id', $inventoryId)->orderBy('created_at', 'desc')->get();
        return view('inventory_transaction.index', compact('inventory', 'transactions'));
    } else {
        // Mode: Tampilkan semua transaksi
        $transactions = InventoryTransaction::with('inventory')->latest()->get();
        return view('inventory_transaction.index', compact('transactions'));
    }
}



    /**
     * Menampilkan form untuk membuat transaksi baru.
     */
    public function create()
    {
        $inventories = Inventory::all();
        return view('inventory_transaction.create', compact('inventories'));
    }

    /**
     * Menyimpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        // Proses penyesuaian stok
        if ($request->tipe === 'keluar') {
            if ($inventory->stok < $request->jumlah) {
                return back()->withErrors(['jumlah' => 'Stok tidak mencukupi untuk transaksi keluar.'])->withInput();
            }
            $inventory->stok -= $request->jumlah;
        } else {
            $inventory->stok += $request->jumlah;
        }

        $inventory->save();

        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'tipe' => $request->tipe,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('inventory-transaction.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Menghapus transaksi dan mengembalikan stok.
     */
    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        $inventory = $inventoryTransaction->inventory;

        // Rollback stok
        if ($inventoryTransaction->tipe === 'masuk') {
            $inventory->stok -= $inventoryTransaction->jumlah;
        } else {
            $inventory->stok += $inventoryTransaction->jumlah;
        }

        // Cek agar stok tidak negatif
        if ($inventory->stok < 0) {
            return back()->with('error', 'Tidak bisa menghapus transaksi karena akan menyebabkan stok negatif.');
        }

        $inventory->save();
        $inventoryTransaction->delete();

        return redirect()->route('inventory-transaction.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}

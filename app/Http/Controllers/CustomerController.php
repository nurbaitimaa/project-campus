<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_customer' => 'required|unique:customers,kode_customer',
            'nama_customer' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
        ]);

        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Data customer berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'kode_customer' => 'required|unique:customers,kode_customer,' . $customer->id,
            'nama_customer' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
        ]);

        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Data customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Data customer berhasil dihapus.');
    }
}

@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Daftar Budget Marketing</h3>

    @if(auth()->user()->role === 'manager')
        <a href="{{ route('marketing-budgets.create') }}" class="btn btn-success mb-3">+ Tambah Budget</a>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Program</th>
                <th>Tahun Anggaran</th>
                <th>Nilai</th>
                <th>Sisa</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($budgets as $b)
                <tr>
                    <td>{{ $b->customer->nama_customer }}</td>
                    <td>{{ $b->program->nama_program ?? '-' }}</td>
                    <td>{{ $b->tahun_anggaran }}</td>
                    <td>Rp {{ number_format($b->nilai_budget, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($b->sisa_budget, 0, ',', '.') }}</td>
                    <td>{{ $b->keterangan }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $budgets->links() }}
</div>
@endsection

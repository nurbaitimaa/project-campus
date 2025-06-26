@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Input Budget Marketing</h3>

    <form action="{{ route('marketing-budgets.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                <option value="">-- Pilih Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->nama_customer }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Program (Opsional)</label>
            <select name="program_id" class="form-control">
                <option value="">-- Tanpa Program Spesifik --</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->nama_program }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
    <label for="tahun_anggaran">Tahun Anggaran</label>
    <select name="tahun_anggaran" class="form-control" required>
        @for ($year = now()->year; $year <= now()->year + 3; $year++)
            <option value="{{ $year }}">{{ $year }}</option>
        @endfor
    </select>
</div>


        <div class="mb-3">
            <label>Nilai Budget</label>
            <input type="number" name="nilai_budget" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('marketing-budgets.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection

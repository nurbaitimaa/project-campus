@extends('layouts.app') {{-- Admin Marketing - Edit Program Berjalan --}}
@section('content')
<div class="container mt-4">
    <h3>Edit Program Berjalan</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Ada beberapa masalah:<br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('program-berjalan.update', $program->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tanggal Input</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $program->tanggal) }}" required>
        </div>

        <div class="mb-3">
            <label>Customer</label>
            <select name="kode_customer" id="kode_customer" class="form-control" required>
                <option value="">-- Pilih Customer --</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->kode_customer }}" {{ $program->kode_customer == $customer->kode_customer ? 'selected' : '' }}>
                        {{ $customer->nama_customer }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Program</label>
            <select name="kode_program" id="kode_program" class="form-control" required>
                <option value="">-- Pilih Program --</option>
                @foreach ($programs as $prog)
                    <option value="{{ $prog->kode_program }}" {{ $program->kode_program == $prog->kode_program ? 'selected' : '' }}>
                        {{ $prog->nama_program }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Periode Program</label>
            <div class="row">
                <div class="col">
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $program->start_date) }}" required>
                </div>
                <div class="col">
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $program->end_date) }}" required>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label>Target Reward</label>
            <input type="text" name="target" class="form-control" value="{{ old('target', $program->target) }}">
        </div>

        <div class="mb-3">
            <label>PIC</label>
            <input type="text" name="pic" class="form-control" value="{{ old('pic', $program->pic) }}">
        </div>

        <div class="mb-3">
            <label>Deskripsi / Mekanisme</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $program->keterangan) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Budget (Rp)</label>
            <input type="number" name="budget" class="form-control" value="{{ old('budget', $program->budget) }}">
        </div>

        <div class="mb-3">
            <label>Upload File Baru (jika ingin mengganti)</label>
            <input type="file" name="file_path" class="form-control">
            @if ($program->file_path)
                <small class="text-muted">File saat ini: <a href="{{ asset('storage/' . $program->file_path) }}" target="_blank">Lihat</a></small>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('program-berjalan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#kode_customer').select2({ placeholder: "Pilih Customer" });
        $('#kode_program').select2({ placeholder: "Pilih Program" });
    });
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

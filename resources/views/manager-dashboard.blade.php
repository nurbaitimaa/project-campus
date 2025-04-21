@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-dark text-white min-vh-100 p-3">
            <h5 class="fw-bold">Manager Mazzoni</h5>
            <ul class="nav flex-column mt-4">
                <li class="nav-item"><a class="nav-link text-white" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Persetujuan Program</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Persetujuan Klaim</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Budget</a></li> <!-- ✅ Tambahan -->
                <li class="nav-item"><a class="nav-link text-white" href="#">Laporan</a></li>
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-light mt-4 w-100">Keluar</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Dashboard Manager</h3>
                <span>Selamat datang, {{ Auth::user()->name }}</span>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card text-center mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Program Menunggu Persetujuan</h5>
                            <h2 class="text-primary">5</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-center mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Klaim Menunggu Persetujuan</h5>
                            <h2 class="text-warning">3</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-primary text-white min-vh-100 p-3">
            <h5 class="fw-bold">Marketing Mazzoni</h5>
            <ul class="nav flex-column mt-4">
                <li class="nav-item"><a class="nav-link text-white" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">File Manager</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Absensi</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Program</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Inventory</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Laporan</a></li>
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-light mt-4 w-100">Keluar</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-5">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Dashboard</h3>
                <span>Hello, {{ Auth::user()->name }}</span>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h1>3</h1>
                            <p class="mb-0">Program Berjalan</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <h1>Rp 200.000.000</h1>
                            <p class="mb-0">Sisa Budget</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

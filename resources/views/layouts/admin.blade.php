{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-md px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">Admin Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium">Halo, {{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-md">
                <div class="p-6 text-lg font-semibold border-b">Menu</div>
                <nav class="mt-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block px-6 py-2 hover:bg-gray-100">Dashboard</a>
<!-- Dropdown Master Data -->
<div x-data="{ open: false }" class="px-6 py-2">
    <button @click="open = !open" class="w-full text-left flex justify-between items-center hover:bg-gray-100">
        <span>Master Data</span>
        <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div x-show="open" class="mt-2 space-y-1 pl-4" x-cloak>
        <a href="{{ route('programs.index') }}" class="block py-1 hover:underline">Program</a>
        <a href="{{ route('customers.index') }}" class="block py-1 hover:underline">Customer</a>
        <a href="{{ route('sales-marketing.index') }}" class="block py-1 hover:underline">Sales Marketing</a>
    </div>
</div>

                    <a href="#" class="block px-6 py-2 hover:bg-gray-100">Program</a>
                    <a href="#" class="block px-6 py-2 hover:bg-gray-100">Absensi</a>
                    <a href="#" class="block px-6 py-2 hover:bg-gray-100">Inventory</a>
                    <a href="#" class="block px-6 py-2 hover:bg-gray-100">Program & Klaim</a>
                    <a href="#" class="block px-6 py-2 hover:bg-gray-100">Laporan</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <!-- Main Content -->
<main class="flex-1 p-6">

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700">Jumlah Program</h2>
            <p class="text-3xl font-bold mt-2">25</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700">Jumlah Klaim</h2>
            <p class="text-3xl font-bold mt-2">14</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700">Grafik Statistik</h2>
            <div class="mt-2 text-gray-500">[Placeholder Grafik]</div>
        </div>
    </div>
</main>

            </main>
        </div>
    </div>
</body>
</html>

    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('page-title', 'Dashboard') - Mazzoni</title>
        
        {{-- Vite untuk Tailwind CSS --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Font Google --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        {{-- Alpine.js untuk interaktivitas --}}
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        {{-- Chart.js untuk grafik --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-slate-100 text-slate-800">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            @include('layouts.navbar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                <!-- Header -->
                <header class="bg-white shadow-sm p-4 flex justify-between items-center no-print">
                    <div class="font-semibold text-lg text-slate-700">
                        @yield('page-title', 'Dashboard')
                    </div>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 text-sm hover:text-blue-600">
                            <span>Halo, {{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6 lg:p-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
    </html>
    
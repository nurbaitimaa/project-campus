<aside class="w-64 bg-slate-800 text-slate-300 flex-col no-print hidden lg:flex">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-slate-700">
        <a href="{{ route('manager.dashboard') }}" class="flex items-center space-x-3">
            <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" class="h-8">
            <span class="text-white text-lg font-bold tracking-wider">MAZZONI</span>
        </a>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 px-4 py-4 space-y-2">
        <a href="{{ route('manager.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-md transition-colors duration-200 {{ request()->routeIs('manager.dashboard') ? 'bg-rose-600 text-white' : 'hover:bg-slate-700' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('approval.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-md transition-colors duration-200 {{ request()->routeIs('approval.*') ? 'bg-rose-600 text-white' : 'hover:bg-slate-700' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Persetujuan Klaim</span>
        </a>

        <a href="{{ route('marketing-budgets.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-md transition-colors duration-200 {{ request()->routeIs('marketing-budgets.*') ? 'bg-rose-600 text-white' : 'hover:bg-slate-700' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span>Marketing Budget</span>
        </a>
        
        <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-md transition-colors duration-200 {{ request()->routeIs('reports.*') ? 'bg-rose-600 text-white' : 'hover:bg-slate-700' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Laporan</span>
        </a>
    </nav>
</aside>

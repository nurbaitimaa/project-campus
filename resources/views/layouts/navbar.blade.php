<aside class="w-64 bg-slate-800 text-slate-300 flex flex-col no-print">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-slate-700">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
            <img src="{{ asset('image/logo-mazzoni.png') }}" alt="Logo" class="h-8">
            <span class="text-white text-lg font-bold">Mazzoni</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md hover:bg-slate-700 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white' : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dashboard</span>
        </a>

        <!-- Master Data Dropdown -->
        <div x-data="{ open: {{ request()->is('programs*') || request()->is('customers*') || request()->is('sales-marketing*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between space-x-3 px-4 py-2 rounded-md hover:bg-slate-700">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span>Master Data</span>
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 space-y-1 pl-8">
                <a href="{{ route('programs.index') }}" class="block px-4 py-1.5 rounded-md text-sm hover:bg-slate-700 {{ request()->routeIs('programs.*') ? 'text-white' : '' }}">Program</a>
                <a href="{{ route('customers.index') }}" class="block px-4 py-1.5 rounded-md text-sm hover:bg-slate-700 {{ request()->routeIs('customers.*') ? 'text-white' : '' }}">Customer</a>
                <a href="{{ route('sales-marketing.index') }}" class="block px-4 py-1.5 rounded-md text-sm hover:bg-slate-700 {{ request()->routeIs('sales-marketing.*') ? 'text-white' : '' }}">Sales Marketing</a>
            </div>
        </div>

        <!-- Program & Klaim Dropdown -->
        <div x-data="{ open: {{ request()->is('program-berjalan*') || request()->is('program-claims*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between space-x-3 px-4 py-2 rounded-md hover:bg-slate-700">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Program & Klaim</span>
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="open" x-cloak class="mt-1 space-y-1 pl-8">
                <a href="{{ route('program-berjalan.index') }}" class="block px-4 py-1.5 rounded-md text-sm hover:bg-slate-700 {{ request()->routeIs('program-berjalan.*') ? 'text-white' : '' }}">Program Berjalan</a>
                <a href="{{ route('program-claims.index') }}" class="block px-4 py-1.5 rounded-md text-sm hover:bg-slate-700 {{ request()->routeIs('program-claims.*') ? 'text-white' : '' }}">Klaim Program</a>
            </div>
        </div>

        <a href="{{ route('absensi.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md hover:bg-slate-700 {{ request()->routeIs('absensi.*') ? 'bg-slate-900 text-white' : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>Absensi</span>
        </a>
        
        <a href="{{ route('inventory.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md hover:bg-slate-700 {{ request()->routeIs('inventory.*') || request()->routeIs('inventory-transaction.*') ? 'bg-slate-900 text-white' : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            <span>Inventory</span>
        </a>

        <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-md hover:bg-slate-700 {{ request()->routeIs('reports.*') ? 'bg-slate-900 text-white' : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Laporan</span>
        </a>
    </nav>
</aside>

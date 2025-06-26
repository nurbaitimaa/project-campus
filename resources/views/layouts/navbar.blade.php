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
        <a href="{{ route('absensi.index') }}" class="block px-6 py-2 hover:bg-gray-100">Absensi</a>
        <a href="{{ route('inventory.index') }}" class="block px-6 py-2 hover:bg-gray-100">Inventory</a>
        
                            <!-- Dropdown Program & Klaim -->
        <div x-data="{ open: false }" class="px-6 py-2">
            <button @click="open = !open" class="w-full text-left flex justify-between items-center hover:bg-gray-100">
                <span>Program & Klaim</span>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" class="mt-2 space-y-1 pl-4" x-cloak>
                <a href="{{ route('program-berjalan.index') }}" class="block py-1 hover:underline">Program Berjalan</a>
                <a href="{{ route('program-claims.index') }}" class="block py-1 hover:underline">Klaim Program</a>
                {{-- Tambah sub-menu klaim jika ada di sini --}}
            </div>
        </div>

                            <a href="#" class="block px-6 py-2 hover:bg-gray-100">Laporan</a>
                        </nav>
                    </aside>
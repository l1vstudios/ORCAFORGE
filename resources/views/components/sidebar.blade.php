<script src="https://unpkg.com/lucide@latest"></script>
<style>
  .scrollbar-hide::-webkit-scrollbar {
  width: 0px;
  height: 0px;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
<aside id="sidebar"
    class="w-64 bg-white border-r border-gray-200 shadow-sm flex flex-col h-screen overflow-hidden transition-all duration-300">

    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <div class="bg-white text-white p-1.5 rounded-lg flex items-center justify-center">
                <span class="text-xl">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT9zFRQAbDsrkXwAJkZYVE-AIO3OyfVxYYm9w&s"
                         alt="Orca Logo" class="w-6 h-6 rounded">
                </span>
            </div>
            <span class="text-base font-semibold sidebar-text whitespace-nowrap transition-all duration-300">
                ORCAFORGE - BETA
            </span>
        </div>

        <button id="collapseBtn"
            class="hidden md:block text-gray-500 hover:text-indigo-600 focus:outline-none flex-shrink-0 transition-transform duration-300">
            <i data-lucide="chevron-left" class="w-5 h-5" id="chevronIcon"></i>
        </button>

        <button id="closeMobileSidebar"
            class="md:hidden text-gray-500 hover:text-red-600 focus:outline-none">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto overflow-x-hidden scrollbar-hide">

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mb-2">Menu</p>

        <a href="/"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150
           {{ request()->is('dashboard') ? 'bg-gray-900 text-white shadow-md' : 'text-gray-700 hover:bg-gray-900 hover:text-white' }}">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium transition-all duration-300">Dashboard</span>
        </a>

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">Database</p>

        <a href="{{ route('brainsoft_database') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150
           {{ request()->routeIs('brainsoft_database') ? 'bg-gray-900 text-white shadow-md' : 'text-gray-700 hover:bg-gray-900 hover:text-white' }}">
            <i data-lucide="database" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Orca Database</span>
        </a>

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">Magic Builder</p>

        <a href="{{ route('orca_menu.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150
           {{ request()->routeIs('orca_menu.*') ? 'bg-gray-900 text-white shadow-md' : 'text-gray-700 hover:bg-gray-900 hover:text-white' }}">
            <i data-lucide="wand-2" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Orca Magic Builder</span>
        </a>


        <!-- <a href="{{ route('orca_menu.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150
           {{ request()->routeIs('orca_menu.*') ? 'bg-gray-900 text-white shadow-md' : 'text-gray-700 hover:bg-gray-900 hover:text-white' }}">
            <i data-lucide="wand-2" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Orca Page Builder</span>
        </a> -->

        @php
            use App\Models\OrcaMenu;
            $menus = OrcaMenu::orderBy('nama_menu')->get();
        @endphp

        @if ($menus->count())
            <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">
                ORCA MENU BUILDER LIST
            </p>

            @foreach ($menus as $menu)
                @php
                    $menuRoute = 'orca_' . strtolower($menu->nama_menu) . '.index';
                    $isActive = request()->routeIs($menuRoute);
                @endphp

                <a href="{{ route($menuRoute) }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150
                   {{ $isActive ? 'bg-gray-900 text-white shadow-md' : 'text-gray-700 hover:bg-gray-900 hover:text-white' }}">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span class="sidebar-text font-medium">{{ $menu->nama_menu }}</span>
                </a>
            @endforeach
        @endif

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">Model</p>

        <a href="{{ route('orca_model.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150
           {{ request()->routeIs('orca_model.*') ? 'bg-gray-900 text-white shadow-md' : 'text-gray-700 hover:bg-gray-900 hover:text-white' }}">
            <i data-lucide="database" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Orca Model</span>
        </a>

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">Kontroller</p>

        <a href="{{ route('orca_base.index') }}"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150
           {{ request()->routeIs('orca_base.*') ? 'bg-gray-900 text-white shadow-md' : 'text-gray-700 hover:bg-gray-900 hover:text-white' }}">
            <i data-lucide="settings" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Orca Kontroller</span>
        </a>

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">Hak Akses</p>

        <a href="#"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-900 hover:text-white transition">
            <i data-lucide="shield-check" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Orca Hak Akses</span>
        </a>

        <a href="#"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-900 hover:text-white transition">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Orca Role</span>
        </a>

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">Komponen</p>

        <a href="#"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-900 hover:text-white transition">
            <i data-lucide="package" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Orca Komponen</span>
        </a>

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">Bantuan</p>

        <a href="#"
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-gray-900 hover:text-white transition">
            <i data-lucide="help-circle" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Pusat Bantuan</span>
        </a>

        <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold sidebar-text px-3 mt-4 mb-2">Autentikasi</p>

        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
           class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-red-700 hover:text-white transition-all duration-200">
            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span class="sidebar-text font-medium">Logout</span>
        </a>

        <form id="logout-form" action="" method="POST" class="hidden">@csrf</form>
    </nav>
</aside>

{{-- SCRIPT HANDLER --}}
<script>
    lucide.createIcons();
    document.getElementById('closeMobileSidebar')?.addEventListener('click', () => {
        document.getElementById('sidebarWrapper').classList.add('-translate-x-full');
        document.getElementById('overlay').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.body.style.overflow = 'auto';
    });
</script>

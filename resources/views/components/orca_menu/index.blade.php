@extends('orcaforge::layouts.app')

@section('title', 'Orca Menu - ORCAFORGE')

@section('content')
<div class="space-y-6">
    @include('orcaforge::components.header')

    <div class="bg-white rounded-xl shadow-md p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between text-black gap-4">
        <div class="flex items-center gap-3">
            <div class="relative bg-black text-white p-2 rounded-lg flex items-center justify-center overflow-hidden border border-gray-700 shadow-sm">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.1)_0px,_rgba(255,255,255,0.1)_2px,_transparent_2px,_transparent_6px)]"></span>
                <i data-lucide="list" class="w-5 h-5 relative z-10 text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Daftar Orca Menu</h3>
                <p class="text-sm text-gray-500">Semua data menu dari <strong>tabel orca_menu</strong></p>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('orca_menu.create') }}"
                class="relative inline-flex items-center gap-2 text-sm text-white font-medium px-3 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition shadow-sm overflow-hidden">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                <span class="relative z-10 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Menu
                </span>
            </a>
            <button onclick="window.location.reload()"
                class="relative inline-flex items-center gap-2 text-sm text-white font-medium px-3 py-2 rounded-lg transition border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                <span class="relative z-10 flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh
                </span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-700 border border-green-200 rounded-lg text-sm shadow-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-red-100 text-red-700 border border-red-200 rounded-lg text-sm shadow-sm flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <form method="GET" action="{{ route('orca_menu.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" placeholder="Cari nama menu..." value="{{ request('search') }}"
                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full sm:w-60">
            <button type="submit"
                class="relative inline-flex items-center gap-1 text-white text-sm font-medium rounded-lg border border-gray-700 bg-black hover:bg-gray-800 px-4 py-2 shadow-sm overflow-hidden">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                <span class="relative z-10 flex items-center gap-1">
                    <i data-lucide="search" class="w-4 h-4"></i> Cari
                </span>
            </button>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-xs">
                    <th class="px-4 py-2 text-left w-12">#</th>
                    <th class="px-4 py-2 text-left">Nama Menu</th>
                    <th class="px-4 py-2 text-left">Reference Pages</th>
                    <th class="px-4 py-2 text-left">Reference Controller</th>
                    <th class="px-4 py-2 text-left">Reference Model</th>
                    <th class="px-4 py-2 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $index => $menu)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-2 text-gray-500">{{ $menus->firstItem() + $index }}</td>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $menu->nama_menu }}</td>
                        <td class="px-4 py-2 text-gray-600">
                            @if(is_array($menu->reference_pages))
                                <code>{{ json_encode($menu->reference_pages) }}</code>
                            @else
                                <span class="italic text-gray-400">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            @if(is_array($menu->reference_controller))
                                <code>{{ json_encode($menu->reference_controller) }}</code>
                            @else
                                <span class="italic text-gray-400">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            @if(is_array($menu->reference_model))
                                <code>{{ json_encode($menu->reference_model) }}</code>
                            @else
                                <span class="italic text-gray-400">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <!-- <a href="{{ route('orca_menu.edit', $menu->id) }}"
                                    class="relative inline-flex items-center justify-center gap-1 
                                           text-white text-xs font-medium rounded-md transition duration-150 shadow-sm
                                           w-24 h-9 border border-gray-700 bg-black hover:bg-gray-800 overflow-hidden">
                                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                    <span class="relative z-10 flex items-center gap-1">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
                                    </span>
                                </a> -->

                                <form action="{{ route('orca_menu.destroy', $menu->id) }}" method="POST" class="delete-form" data-name="{{ $menu->nama_menu }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="relative inline-flex items-center justify-center gap-1 
                                               text-white text-xs font-medium rounded-md transition duration-150 shadow-sm
                                               w-24 h-9 border border-gray-700 bg-black hover:bg-gray-800 delete-btn overflow-hidden">
                                        <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                        <span class="relative z-10 flex items-center gap-1">
                                            <i data-lucide="trash" class="w-4 h-4"></i> Hapus
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-4 italic">Belum ada data menu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($menus, 'links'))
            <div class="mt-4">
                {{ $menus->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Scripts --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
lucide.createIcons();

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = this.closest('.delete-form');
        const name = form.dataset.name;

        Swal.fire({
            title: 'Hapus Menu?',
            html: `Menu <b>${name}</b> akan dihapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endsection

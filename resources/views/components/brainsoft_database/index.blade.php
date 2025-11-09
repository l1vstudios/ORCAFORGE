@extends('layouts.app')

@section('title', 'Database - ORCAFORGE')

@section('content')
<div class="space-y-6">
    @include('components.header')

    <div class="bg-white rounded-xl shadow-md p-5 flex items-center justify-between text-black">
        <div class="flex items-center gap-3">
            <div class="bg-white/20 p-2 rounded-lg">
                <i data-lucide="database" class="w-6 h-6 text-black"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Database Aktif</h3>
                <p class="text-sm text-black-100">Tabel di <strong>{{ env('DB_DATABASE') }}</strong></p>
            </div>
        </div>

        <button onclick="window.location.reload()" 
            class="relative inline-flex items-center gap-2 text-sm text-white font-medium px-3 py-2 rounded-lg transition border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden">
            <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
            <span class="relative z-10 flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh
            </span>
        </button>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-700 border border-green-200 rounded-lg text-sm shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 inline-block mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
        <form action="{{ route('brainsoft_database.add_table') }}" method="POST" class="flex flex-wrap items-end gap-4">
            @csrf

            <div class="flex flex-col">
                <label class="text-xs font-semibold text-gray-600 mb-1">Nama Tabel</label>
                <input type="text" name="name" placeholder="contoh: users" required
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-52 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div class="flex flex-col">
                <label class="text-xs font-semibold text-gray-600 mb-1">Jumlah Kolom Awal</label>
                <input type="number" name="columns" min="1" max="50" value="1"
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-32 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <button type="submit"
                class="relative inline-flex items-center gap-2 text-sm font-medium text-white px-4 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                <span class="relative z-10 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Tabel
                </span>
            </button>

            <div class="hidden md:block h-8 w-px bg-gray-300 mx-2"></div>

            <div class="flex items-end gap-2">
                <form method="GET" action="{{ route('brainsoft_database') }}" class="flex items-center gap-2">
                    <input type="text" name="search" placeholder="Cari nama tabel..."
                          value="{{ $search ?? '' }}"
                          class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-60 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button type="submit"
                        class="relative inline-flex items-center gap-1 text-white text-sm font-medium rounded-lg border border-gray-700 bg-black hover:bg-gray-800 px-4 py-2 shadow-sm overflow-hidden">
                        <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                        <span class="relative z-10 flex items-center gap-1">
                            <i data-lucide="search" class="w-4 h-4"></i> Cari
                        </span>
                    </button>
                </form>

                <a href="{{ route('brainsoft_database.export_sql') }}"
                    class="relative inline-flex items-center gap-1 text-white text-sm font-medium rounded-lg border border-gray-700 bg-black hover:bg-gray-800 px-4 py-2 shadow-sm overflow-hidden">
                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                    <span class="relative z-10 flex items-center gap-1">
                        <i data-lucide="download" class="w-4 h-4"></i> TO SQL
                    </span>
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-xs">
                    <th class="px-4 py-2 text-left w-12">#</th>
                    <th class="px-4 py-2 text-left">Nama Tabel</th>
                    <th class="px-4 py-2 text-left">Jumlah Data</th>
                    <th class="px-4 py-2 text-center w-48">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tableNames as $index => $table)
                    @php $count = DB::table($table)->count(); @endphp
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-2 text-gray-500">{{ $tableNames->firstItem() + $index }}</td>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $table }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold 
                                {{ $count > 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $count }} Data
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('brainsoft_database.show', $table) }}"
                                    class="relative inline-flex items-center justify-center gap-1 
                                           text-white text-xs font-medium rounded-md shadow-sm
                                           w-24 h-9 border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                    <span class="relative z-10 flex items-center gap-1">
                                        <i data-lucide="eye" class="w-4 h-4"></i> Detail
                                    </span>
                                </a>

                                <form action="{{ route('brainsoft_database.delete_table', $table) }}" 
                                      method="POST" class="delete-form" data-table="{{ $table }}">
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
                        <td colspan="4" class="text-center text-black py-4 italic">Belum ada tabel di database ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $tableNames->links() }}
        </div>
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
        const table = form.dataset.table;

        Swal.fire({
            title: 'Hapus Tabel?',
            html: `Tabel <b>${table}</b> dan semua datanya akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection

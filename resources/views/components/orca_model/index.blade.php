@extends('layouts.app')

@section('title', 'Model - ORCAFORGE')

@section('content')
<div class="space-y-6">
    @include('components.header')

    <div class="bg-white rounded-xl shadow-md p-5 flex items-center justify-between text-black">
        <div class="flex items-center gap-3">
            <div class="bg-gray-900 text-white p-2 rounded-lg flex items-center justify-center shadow-sm">
                <i data-lucide="database" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Daftar Model</h3>
                <p class="text-sm text-gray-500">Semua model di <strong>app/Models</strong></p>
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
    @if(session('error'))
        <div class="p-3 bg-red-100 text-red-700 border border-red-200 rounded-lg text-sm shadow-sm">
            <i data-lucide="alert-triangle" class="w-4 h-4 inline-block mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 flex items-center justify-between">
        <form method="GET" action="{{ route('orca_model.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" placeholder="Cari model..." value="{{ $search ?? '' }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-60 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
                    <th class="px-4 py-2 text-left">Nama Model</th>
                    <th class="px-4 py-2 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($models as $index => $model)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-2 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $model }}</td>
                        <td class="px-4 py-2 text-center">
                            <form action="{{ route('orca_model.destroy', $model) }}" method="POST" class="delete-form" data-model="{{ $model }}">
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-500 py-4 italic">Belum ada model.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
lucide.createIcons();

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = this.closest('.delete-form');
        const model = form.dataset.model;

        Swal.fire({
            title: 'Hapus Model?',
            html: `Model <b>${model}</b> akan dihapus dari folder <code>app/Models</code>.`,
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

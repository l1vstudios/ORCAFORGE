@extends('orcaforge::layouts.app')
@section('title', 'Berita - ORCAFORGE')
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
                <h3 class="text-lg font-semibold text-gray-900">Daftar Berita</h3>
                <p class="text-sm text-gray-500">Semua data dari tabel <strong>berita</strong></p>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('orca_berita.create') }}"
                class="relative inline-flex items-center justify-center gap-2 text-sm text-white font-medium w-28 h-9 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                <span class="relative z-10 flex items-center gap-2"><i data-lucide="plus" class="w-4 h-4"></i> Tambah</span>
            </a>
            <a href="{{ url()->current() }}"
                class="relative inline-flex items-center justify-center gap-2 text-sm text-white font-medium w-28 h-9 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                <span class="relative z-10 flex items-center gap-2"><i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh</span>
            </a>
            <a href="{{ route('orca_berita.export') }}"
                class="relative inline-flex items-center justify-center gap-2 text-sm text-white font-medium w-28 h-9 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                <span class="relative z-10 flex items-center gap-2"><i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Excel</span>
            </a>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-wrap gap-3 items-end">
        <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex flex-col">
                <label class="text-xs text-gray-500 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Ketik kata kunci..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-60">
            </div>
            <div class="flex flex-col">
                <label class="text-xs text-gray-500 mb-1 invisible">Cari</label>
                <button type="submit"
                    class="relative inline-flex items-center justify-center gap-2 text-sm text-white font-medium w-28 h-9 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 overflow-hidden transition">
                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                    <span class="relative z-10 flex items-center gap-2"><i data-lucide="search" class="w-4 h-4"></i> Filter</span>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 overflow-x-auto">
        <table class="min-w-full text-sm border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2 w-10">#</th>
                    <th class='px-4 py-2 text-left capitalize'>nama_berita</th>
<th class='px-4 py-2 text-left capitalize'>deskripsi_berita</th>
<th class='px-4 py-2 text-left capitalize'>gambar_berita</th>

                    <th class="px-4 py-2 text-center w-48">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-2 text-gray-500">{{ $data->firstItem() + $index }}</td>
                        <td class='px-4 py-2 text-gray-800'>{{ $item->nama_berita }}</td>
<td class='px-4 py-2 text-gray-800'>{{ $item->deskripsi_berita }}</td>
<td class="px-4 py-2 text-left">
    @if($item->gambar_berita)
        @php $url = asset('storage/' . $item->gambar_berita); @endphp
        <a href="{{ $url }}" target="_blank"
          class="inline-flex items-center gap-1 text-sm font-medium text-white bg-black border border-gray-700 rounded-md px-3 py-1.5 hover:bg-gray-800 transition-all overflow-hidden relative">
            <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.1)_0px,_rgba(255,255,255,0.1)_2px,_transparent_2px,_transparent_6px)]"></span>
            <span class="relative z-10 flex items-center gap-1">
                <i data-lucide="external-link" class="w-4 h-4"></i> Lihat File
            </span>
        </a>
    @else
        <span class="text-gray-400 italic">Tidak ada</span>
    @endif
</td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('orca_berita.edit', $item->id) }}"
                                    class="relative inline-flex items-center justify-center gap-1 text-white text-xs font-medium rounded-md border border-gray-700 bg-black hover:bg-gray-800 w-24 h-9 overflow-hidden">
                                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                    <span class="relative z-10 flex items-center gap-1 justify-center">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i> Edit
                                    </span>
                                </a>
                                <form action="{{ route('orca_berita.destroy', $item->id) }}" method="POST" class="delete-form" data-name="{{ $item->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="relative inline-flex items-center justify-center gap-1 text-white text-xs font-medium rounded-md border border-gray-700 bg-black hover:bg-gray-800 w-24 h-9 delete-btn overflow-hidden">
                                        <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                                        <span class="relative z-10 flex items-center gap-1 justify-center">
                                            <i data-lucide="trash" class="w-4 h-4"></i> Hapus
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="100%" class="text-center text-gray-500 py-4 italic">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($data->hasPages())
        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
            <div class="text-sm text-gray-600">
                Menampilkan <span class="font-semibold">{{ $data->firstItem() }}</span>–<span class="font-semibold">{{ $data->lastItem() }}</span> dari <span class="font-semibold">{{ $data->total() }}</span> entri
            </div>
            <div class="flex items-center gap-2">
                {{ $data->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
lucide.createIcons();
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = this.closest('.delete-form');
        const name = form.dataset.name;
        Swal.fire({
            title: 'Hapus Data?',
            html: `Data <b>${name}</b> akan dihapus permanen.`,
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
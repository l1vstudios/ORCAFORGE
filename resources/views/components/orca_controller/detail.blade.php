@extends('layouts.app')

@section('title', 'Edit Struktur Tabel - ORCAFORGE')

@section('content')
<div class="space-y-4">
    @include('components.header')

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                <i data-lucide="table" class="w-5 h-5 text-white bg-black rounded p-1"></i>
                Edit Struktur: <span class="text-indigo-600 font-bold">{{ $table }}</span>
            </h3>

            <a href="{{ route('brainsoft_database') }}" 
                class="relative inline-flex items-center gap-2 text-sm text-white px-3 py-2 rounded-md border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden transition">
                <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                <span class="relative z-10 flex items-center gap-2">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-white"></i> Kembali
                </span>
            </a>
        </div>

        @if(session('success'))
            <div class="p-3 mb-4 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-3 mb-4 bg-red-100 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <form action="{{ route('brainsoft_database.add_column', $table) }}" method="POST" class="mb-6">
            @csrf
            <div class="flex flex-wrap items-end gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                <div class="flex flex-col">
                    <label class="text-xs text-gray-600">Nama Kolom</label>
                    <input type="text" name="name" required
                           class="border border-gray-300 rounded-md px-2 py-1 text-sm w-40">
                </div>

                <div class="flex flex-col">
                    <label class="text-xs text-gray-600">Tipe Data</label>
                    <div x-data="{ open: false, search: '', selected: '{{ $types[0] ?? '' }}' }" class="relative w-40">
                        <button type="button"
                            @click="open = !open"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm w-full text-left bg-white flex justify-between items-center">
                            <span x-text="selected || 'Pilih tipe data'"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                        </button>

                        <div x-show="open" @click.outside="open = false"
                             class="absolute mt-1 bg-white border border-gray-200 rounded-md shadow-lg w-full z-50">
                            <input type="text" x-model="search" placeholder="Cari..."
                                   class="w-full border-b border-gray-200 px-2 py-1 text-sm focus:outline-none">
                            <ul class="max-h-40 overflow-y-auto">
                                @foreach($types as $type)
                                    <li @click="selected='{{ $type }}'; open=false; $refs.input.value='{{ $type }}'"
                                        x-show="'{{ strtolower($type) }}'.includes(search.toLowerCase())"
                                        class="px-2 py-1 text-sm hover:bg-indigo-100 cursor-pointer">
                                        {{ $type }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <input type="hidden" x-ref="input" name="type" :value="selected">
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs text-gray-600">Length</label>
                    <input type="text" name="length"
                           class="border border-gray-300 rounded-md px-2 py-1 text-sm w-28 data-length">
                </div>

                <div class="flex flex-col">
                    <label class="text-xs text-gray-600">Null</label>
                    <select name="null"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm w-20">
                        <option value="NO">NO</option>
                        <option value="YES">YES</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs text-gray-600">Default</label>
                    <input type="text" name="default"
                           class="border border-gray-300 rounded-md px-2 py-1 text-sm w-28">
                </div>

                <div class="flex flex-col">
                    <label class="text-xs text-gray-600">Auto Increment</label>
                    <input type="checkbox" name="auto_increment" class="mt-2">
                </div>

                <div class="ml-auto">
                    <button type="submit"
                            class="relative inline-flex items-center gap-2 text-sm font-medium text-white px-3 py-2 rounded-md border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden transition">
                        <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                        <span class="relative z-10 flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4 text-white"></i> Tambah Kolom
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <form action="{{ route('brainsoft_database.update_all', $table) }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2">Nama Kolom</th>
                            <th class="px-3 py-2">Tipe Data</th>
                            <th class="px-3 py-2">Length</th>
                            <th class="px-3 py-2">Null</th>
                            <th class="px-3 py-2">Default</th>
                            <th class="px-3 py-2">AI</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
@foreach($columns as $i => $col)
    @php
        $colType = preg_replace('/\(.*/', '', strtoupper($col->Type));
        $colLength = preg_match('/\((.*?)\)/', $col->Type, $m) ? $m[1] : '';
    @endphp
    <tr class="border-t hover:bg-gray-50 transition">
        <td class="px-3 py-2">
            <input type="hidden" name="columns[{{ $i }}][old_name]" value="{{ $col->Field }}">
            <input type="text" name="columns[{{ $i }}][name]" value="{{ $col->Field }}"
                   class="border border-gray-300 rounded-md px-2 py-1 text-xs w-full text-gray-800">
        </td>

        <td class="px-3 py-2">
            <select name="columns[{{ $i }}][type]"
                    class="border border-gray-300 rounded-md px-2 py-1 text-xs text-gray-800 w-full data-type">
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ $colType === $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
        </td>

        <td class="px-3 py-2">
            <input type="text" name="columns[{{ $i }}][length]" value="{{ $colLength }}"
                   class="border border-gray-300 rounded-md px-2 py-1 w-full text-gray-800 text-xs data-length">
        </td>

        <td class="px-3 py-2 text-center">
            <select name="columns[{{ $i }}][null]"
                    class="border border-gray-300 rounded-md px-2 py-1 text-xs text-gray-700">
                <option value="NO" {{ $col->Null === 'NO' ? 'selected' : '' }}>NO</option>
                <option value="YES" {{ $col->Null === 'YES' ? 'selected' : '' }}>YES</option>
            </select>
        </td>

        <td class="px-3 py-2">
            <input type="text" name="columns[{{ $i }}][default]" value="{{ $col->Default }}"
                   class="border border-gray-300 rounded-md px-2 py-1 w-full text-gray-800 text-xs">
        </td>

        <td class="px-3 py-2 text-center">
            <input type="checkbox" name="columns[{{ $i }}][auto_increment]"
                   {{ str_contains($col->Extra, 'auto_increment') ? 'checked' : '' }}>
        </td>

        <td class="px-3 py-2 text-center">
            <form action="{{ route('brainsoft_database.delete_column', [$table, $col->Field]) }}"
                  method="POST" class="delete-form" data-column="{{ $col->Field }}">
                @csrf
                @method('DELETE')
                <button type="button"
                    class="relative inline-flex items-center justify-center gap-1 text-white text-xs rounded-md border border-gray-700 bg-black hover:bg-gray-800 px-3 py-1.5 overflow-hidden delete-btn transition">
                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                    <span class="relative z-10 flex items-center gap-1">
                        <i data-lucide="trash" class="w-4 h-4 text-white"></i>
                    </span>
                </button>
            </form>
        </td>
    </tr>
@endforeach
</tbody>
                </table>
            </div>

            <div class="flex justify-end mt-4 gap-3">
                  <button type="submit"
                      class="relative inline-flex items-center gap-2 text-sm text-white font-semibold px-4 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden transition">
                      <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                      <span class="relative z-10 flex items-center gap-2">
                          <i data-lucide="save" class="w-4 h-4 text-white"></i> Simpan Semua
                      </span>
                  </button>

                  <button type="button" id="createFillableBtn"
                      class="relative inline-flex items-center gap-2 text-sm text-white font-semibold px-4 py-2 rounded-md border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden transition">
                      <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                      <span class="relative z-10 flex items-center gap-2">
                          <i data-lucide="file-plus" class="w-4 h-4 text-white"></i> Buat Fillable
                      </span>
                  </button>
              </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
lucide.createIcons();

function updateLengthState(select) {
    const row = select.closest('form, tr');
    const input = row.querySelector('.data-length');
    const noLength = ['TEXT','LONGTEXT','MEDIUMTEXT','DATE','DATETIME','TIME','TIMESTAMP','BOOLEAN','JSON'];
    if (noLength.includes(select.value)) {
        input.value = '';
        input.disabled = true;
        input.classList.add('bg-gray-100');
    } else {
        input.disabled = false;
        input.classList.remove('bg-gray-100');
    }
}

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = this.closest('.delete-form');
        const column = form.dataset.column;

        Swal.fire({
            title: 'Hapus Kolom?',
            text: `Apakah Anda yakin ingin menghapus kolom "${column}" dari tabel ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

document.querySelectorAll('.data-type').forEach(sel => {
    updateLengthState(sel);
    sel.addEventListener('change', () => updateLengthState(sel));
});

document.getElementById('createFillableBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Buat Fillable?',
        text: "Semua kolom dari tabel '{{ $table }}' akan dimasukkan ke dalam model.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Buat',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            fetch("{{ route('brainsoft_database.create_fillable') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ table: "{{ $table }}" })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.success, 'success');
                } else {
                    Swal.fire('Gagal!', data.error || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Tidak dapat terhubung ke server', 'error'));
        }
    });
});
</script>
@endsection

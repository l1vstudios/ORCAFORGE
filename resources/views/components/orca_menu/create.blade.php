@extends('layouts.app')

@section('title', 'Tambah Menu - ORCAFORGE')

@section('content')
<div class="space-y-6">
    @include('orcaforge::components.header')

    <div class="bg-white rounded-xl shadow-md p-5 flex items-center justify-between text-black">
        <div class="flex items-center gap-3">
            <div class="bg-black p-2 rounded-lg">
                <i data-lucide="menu" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Magic Orca Menu Builder</h3>
                <p class="text-sm text-gray-500">
                    Pembuatan Menu ini akan otomatis membuat MVC, CRUD, dan route lengkap.
                </p>
            </div>
        </div>

        <a href="{{ route('orca_menu.index') }}" 
           class="relative inline-flex items-center gap-2 text-sm text-white px-3 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden transition">
            <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
            <span class="relative z-10 flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4 text-white"></i> Kembali
            </span>
        </a>
    </div>

    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-6">
@if(session('error'))
    <div class="p-4 mb-4 bg-red-50 border border-red-300 text-red-700 rounded-lg">
        <h4 class="font-semibold mb-2">Error:</h4>
        <p>{{ session('error') }}</p>
        @if(session('exception_message'))
            <p class="text-xs mt-2"><b>Detail:</b> {{ session('exception_message') }}</p>
        @endif
        @if(session('trace'))
            <details class="mt-2 text-xs"><summary>Lihat Trace</summary><pre>{{ session('trace') }}</pre></details>
        @endif
    </div>
@endif

@if(session('success'))
    <div class="p-4 mb-4 bg-green-50 border border-green-300 text-green-700 rounded-lg">
        <h4 class="font-semibold mb-2">Progress:</h4>
        <ul class="list-disc ml-4 text-sm">
            @foreach(session('log_steps', []) as $step)
                <li>{!! $step !!}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form action="{{ route('orca_menu.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
                <input type="text" name="nama_menu"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Contoh: Produk" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Tabel Database</label>
                <select name="tabel"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    required>
                    <option value="">-- Pilih Tabel --</option>
                    @foreach($tables as $t)
                        @php
                            $tableName = array_values((array) $t)[0];
                        @endphp
                        <option value="{{ $tableName }}">{{ $tableName }}</option>
                    @endforeach
                </select>
            </div>

            <div id="formPreview" class="mt-6 hidden">
                <h4 class="text-lg font-semibold mb-3">Preview Form Orca Builder --- (Drag & Drop untuk ubah tipe input)</h4>

                <div class="flex gap-6">
                    <div id="formFields" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl p-4 grid gap-3"></div>

                    <div class="w-56 bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <h5 class="font-semibold text-sm mb-2">Tipe Input</h5>
                        <div id="toolbox" class="grid gap-2">
                            <div draggable="true" data-type="text" class="cursor-move bg-gray-100 rounded-md px-3 py-2 text-center hover:bg-gray-200">Text</div>
                            <div draggable="true" data-type="textarea" class="cursor-move bg-gray-100 rounded-md px-3 py-2 text-center hover:bg-gray-200">Textarea</div>
                            <div draggable="true" data-type="number" class="cursor-move bg-gray-100 rounded-md px-3 py-2 text-center hover:bg-gray-200">Number</div>
                            <div draggable="true" data-type="date" class="cursor-move bg-gray-100 rounded-md px-3 py-2 text-center hover:bg-gray-200">Date</div>
                            <div draggable="true" data-type="file" class="cursor-move bg-gray-100 rounded-md px-3 py-2 text-center hover:bg-gray-200">File</div>
                            <div draggable="true" data-type="select" class="cursor-move bg-gray-100 rounded-md px-3 py-2 text-center hover:bg-gray-200">Select</div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="form_structure" id="form_structure">
            </div>


            <div class="flex justify-end gap-2 mt-6">
                <a href="{{ route('orca_menu.index') }}"
                    class="relative inline-flex items-center gap-2 text-sm text-white px-4 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden transition">
                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                    <span class="relative z-10 flex items-center gap-2">
                        <i data-lucide="x-circle" class="w-4 h-4 text-white"></i> Batal
                    </span>
                </a>

                <button type="submit"
                    class="relative inline-flex items-center gap-2 text-sm text-white px-4 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 shadow-sm overflow-hidden transition">
                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                    <span class="relative z-10 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4 text-white"></i> Simpan Menu
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
<script>
lucide.createIcons();

document.querySelector('select[name="tabel"]').addEventListener('change', function() {
    const table = this.value;
    if (!table) return;

    fetch("{{ route('orca_menu.columns') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ tabel: table })
    })
    .then(res => res.json())
    .then(columns => {
        const container = document.getElementById('formFields');
        container.innerHTML = '';
        columns.forEach(col => {
            if(['id','created_at','updated_at'].includes(col.name)) return;
            const field = document.createElement('div');
            field.className = 'p-3 bg-white border rounded-md flex justify-between items-center';
            field.innerHTML = `
                <div>
                    <label class="font-medium">${col.name}</label>
                    <p class="text-xs text-gray-500">type: <span class="input-type">${col.type}</span></p>
                </div>
                <input type="hidden" name="fields[${col.name}]" value="${col.type}">
            `;
            field.addEventListener('dragover', e => e.preventDefault());
            field.addEventListener('drop', function(e) {
                e.preventDefault();
                const newType = e.dataTransfer.getData('type');
                this.querySelector('.input-type').textContent = newType;
                this.querySelector('input').value = newType;
                updateFormStructure();
            });
            container.appendChild(field);
        });

        document.getElementById('formPreview').classList.remove('hidden');
        updateFormStructure();
    });
});

document.querySelectorAll('#toolbox [draggable="true"]').forEach(el => {
    el.addEventListener('dragstart', e => {
        e.dataTransfer.setData('type', e.target.dataset.type);
    });
});

function updateFormStructure() {
    const fields = [];
    document.querySelectorAll('#formFields input[name^="fields"]').forEach(input => {
        fields.push({
            name: input.name.replace('fields[', '').replace(']', ''),
            type: input.value
        });
    });
    document.getElementById('form_structure').value = JSON.stringify(fields);
}
</script>

@endsection

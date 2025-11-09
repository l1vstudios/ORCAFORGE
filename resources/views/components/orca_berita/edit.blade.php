@extends('orcaforge::layouts.app')
@section('title', 'Tambah Berita')
@section('content')
<div class="space-y-6">
    @include('orcaforge::components.header')
    <div class="bg-white border rounded-xl shadow-sm p-6 max-w-2xl mx-auto">
        <h3 class="text-lg font-semibold mb-4">Tambah Berita</h3>
        <form action="{{ route('orca_berita.update', $item->id) }}" method="POST" enctype="multipart/form-data" method="POST" enctype="multipart/form-data">
            @csrf
@method('PUT')
            
            <div class='mb-4'>
                <label class='block text-sm font-medium text-gray-700 mb-1'>Nama Berita</label>
                <input type='text' name='nama_berita' value='{{ old('nama_berita') }}' class='w-full border rounded-lg px-3 py-2'>
            </div>
            <div class='mb-4'>
                <label class='block text-sm font-medium text-gray-700 mb-1'>Deskripsi Berita</label>
                <textarea name='deskripsi_berita' class='w-full border rounded-lg px-3 py-2'>{{ old('deskripsi_berita') }}</textarea>
            </div>
            <div class='mb-4'>
                <label class='block text-sm font-medium text-gray-700 mb-1'>Gambar Berita</label>
                <input type='file' name='gambar_berita' class='w-full border rounded-lg px-3 py-2'>
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('orca_berita.index') }}"
                    class="relative inline-flex items-center gap-2 text-sm text-white font-medium px-4 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                    <span class="relative z-10 flex items-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i> Batal
                    </span>
                </a>
                <button type="submit"
                    class="relative inline-flex items-center gap-2 text-sm text-white font-medium px-4 py-2 rounded-lg border border-gray-700 bg-black hover:bg-gray-800 transition overflow-hidden">
                    <span class="absolute inset-0 bg-[repeating-linear-gradient(45deg,_rgba(255,255,255,0.15)_0px,_rgba(255,255,255,0.15)_2px,_transparent_2px,_transparent_6px)]"></span>
                    <span class="relative z-10 flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
@endsection
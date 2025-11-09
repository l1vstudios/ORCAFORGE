@extends('layouts.app')

@section('title', 'Dashboard - CMS Builder')

@section('content')
<div class="space-y-4">

    @include('orcaforge::components.header')

    <div class="bg-white rounded-xl shadow-sm flex flex-col md:flex-row items-center gap-4 md:gap-6 p-4 md:p-6">
        <div class="bg-indigo-100 text-indigo-600 p-4 rounded-2xl flex items-center justify-center">
            <i data-lucide="code-2" class="w-10 h-10 md:w-12 md:h-12"></i>
        </div>
        <div>
            <h3 class="text-base md:text-xl font-semibold text-gray-700">
                Selamat datang di <span class="text-indigo-600 font-bold">CMS Builder</span>!
            </h3>
            <p class="text-xs md:text-sm text-gray-500 mt-2 leading-relaxed">
                CMS Builder adalah sistem manajemen konten fleksibel untuk mengelola data, model, menu, dan hak akses secara visual. 
                Dirancang untuk mempercepat pengembangan aplikasi Laravel berbasis modular, tanpa perlu menulis kode dari nol.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 flex items-center gap-3 md:gap-4">
            <div class="bg-indigo-500 text-white p-3 md:p-4 rounded-xl flex items-center justify-center">
                <i data-lucide="layout" class="w-6 h-6 md:w-7 md:h-7"></i>
            </div>
            <div>
                <p class="text-gray-500 text-xs md:text-sm">Total Menu</p>
                <p class="text-xl md:text-2xl font-semibold text-gray-800">24</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 flex items-center gap-3 md:gap-4">
            <div class="bg-green-500 text-white p-3 md:p-4 rounded-xl flex items-center justify-center">
                <i data-lucide="database" class="w-6 h-6 md:w-7 md:h-7"></i>
            </div>
            <div>
                <p class="text-gray-500 text-xs md:text-sm">Total Model</p>
                <p class="text-xl md:text-2xl font-semibold text-gray-800">18</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 flex items-center gap-3 md:gap-4">
            <div class="bg-orange-400 text-white p-3 md:p-4 rounded-xl flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6 md:w-7 md:h-7"></i>
            </div>
            <div>
                <p class="text-gray-500 text-xs md:text-sm">Total Pengguna</p>
                <p class="text-xl md:text-2xl font-semibold text-gray-800">6</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h4 class="text-sm md:text-base text-gray-700 font-semibold mb-4 flex items-center gap-2">
            <i data-lucide="bar-chart-3" class="w-4 h-4 md:w-5 md:h-5 text-indigo-500"></i>
            Statistik Model Aktif per Kategori
        </h4>
        <canvas id="modelChart" height="100"></canvas>
    </div>

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    lucide.createIcons();
    const ctx = document.getElementById('modelChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Menu', 'Model', 'Halaman', 'Kontroller', 'Hak Akses', 'Komponen', 'Helper'
            ],
            datasets: [{
                label: 'Jumlah Aktif',
                data: [12, 8, 5, 6, 3, 7, 2],
                backgroundColor: 'rgba(99, 102, 241, 0.6)',
                borderRadius: 6,
                barThickness: 26
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 2, color: '#6b7280', font: { size: 11 } },
                    grid: { color: '#f3f4f6' }
                },
                x: {
                    ticks: { color: '#6b7280', font: { size: 10 } },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection

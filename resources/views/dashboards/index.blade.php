@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h2
        class="text-2xl font-bold mb-6 bg-clip-text text-transparent 
           bg-gradient-to-r from-green-400 via-blue-500 to-teal-400">
        Selamat Datang
    </h2>

    {{-- GRID UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div
            class="relative p-6 rounded-xl bg-white dark:bg-gray-800 shadow hover:shadow-lg transition transform hover:-translate-y-1 overflow-hidden">
            <i class="fas fa-users absolute top-4 right-4 text-6xl text-pink-200 dark:text-pink-700 opacity-20"></i>
            <p class="text-pink-400 dark:text-pink-300 font-medium">Total User</p>
            <h3 class="text-3xl font-bold mt-2 text-pink-500 dark:text-pink-400">120</h3>
        </div>

        <div
            class="relative p-6 rounded-xl bg-white dark:bg-gray-800 shadow hover:shadow-lg transition transform hover:-translate-y-1 overflow-hidden">
            <i
                class="fas fa-file-invoice-dollar absolute top-4 right-4 text-6xl text-blue-200 dark:text-blue-700 opacity-20"></i>
            <p class="text-blue-400 dark:text-blue-300 font-medium">Penawaran</p>
            <h3 class="text-3xl font-bold mt-2 text-blue-500 dark:text-blue-400">48</h3>
        </div>

        <div
            class="relative p-6 rounded-xl bg-white dark:bg-gray-800 shadow hover:shadow-lg transition transform hover:-translate-y-1 overflow-hidden">
            <i
                class="fas fa-exchange-alt absolute top-4 right-4 text-6xl text-green-200 dark:text-green-700 opacity-20"></i>
            <p class="text-green-400 dark:text-green-300 font-medium">Transaksi</p>
            <h3 class="text-3xl font-bold mt-2 text-green-500 dark:text-green-400">98</h3>
        </div>

        <div
            class="relative p-6 rounded-xl bg-white dark:bg-gray-800 shadow hover:shadow-lg transition transform hover:-translate-y-1 overflow-hidden">
            <i
                class="fas fa-shopping-cart absolute top-4 right-4 text-6xl text-yellow-200 dark:text-yellow-700 opacity-20"></i>
            <p class="text-yellow-400 dark:text-yellow-300 font-medium">Keranjang</p>
            <h3 class="text-3xl font-bold mt-2 text-yellow-500 dark:text-yellow-400">12</h3>
        </div>

    </div>

    {{-- CHART PENJUALAN --}}
    <div class="mt-4 p-4 rounded-lg bg-white dark:bg-gray-800 shadow flex flex-col md:flex-row gap-6">

        <!-- Line Chart -->
        <div class="flex-1 h-54">
            <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-200 flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-500"></i>
                Penjualan Bulanan
            </h3>
            <div class="w-full h-full">
                <canvas id="chartPenjualan" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Table -->
        <div class="flex-1 h-64 max-w-sm flex flex-col">
            <!-- Header tetap di atas -->
            <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-200 flex items-center gap-2">
                <i class="fas fa-boxes text-green-500"></i>
                Distribusi Produk
            </h3>

            <!-- Konten scrollable -->
            <div class="flex-1 overflow-y-auto scrollbar-thin">
                <table
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 rounded-lg shadow text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">Produk</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">Jumlah</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk A</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">12</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">10%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk B</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">15</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">12%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk C</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">8</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">7%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk D</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">20</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">17%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk E</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">5</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">4%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk F</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">18</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">15%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk G</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">7</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">6%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk H</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">10</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">8%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk I</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">6</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">5%</td></tr>
                        <tr><td class="px-4 py-2 text-gray-900 dark:text-gray-100">Produk J</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">14</td><td class="px-4 py-2 text-gray-900 dark:text-gray-100">10%</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TABEL AKTIVITAS TERBARU --}}
    <div class="mt-8 p-4 rounded-xl bg-white dark:bg-gray-800 shadow">
        <h3 class="text-lg font-bold mb-3 text-gray-700 dark:text-gray-200 flex items-center gap-2">
            <i class="fas fa-history text-blue-500"></i>
            Aktivitas Terbaru
        </h3>
        <div class="overflow-x-auto max-h-64">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-2 py-1 text-left font-medium text-gray-700 dark:text-gray-200">User</th>
                        <th class="px-2 py-1 text-left font-medium text-gray-700 dark:text-gray-200">Aktivitas</th>
                        <th class="px-2 py-1 text-left font-medium text-gray-700 dark:text-gray-200">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="px-2 py-1 text-gray-900 dark:text-gray-100">Dedy Angga</td>
                        <td class="px-2 py-1 text-gray-900 dark:text-gray-100">Membuat penawaran baru</td>
                        <td class="px-2 py-1 text-gray-900 dark:text-gray-100">01 Oktober 2025</td>
                    </tr>
                    <tr>
                        <td class="px-2 py-1 text-gray-900 dark:text-gray-100">Rina</td>
                        <td class="px-2 py-1 text-gray-900 dark:text-gray-100">Checkout keranjang</td>
                        <td class="px-2 py-1 text-gray-900 dark:text-gray-100">01 Oktober 2025</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- STATS KECIL --}}
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Pesan Baru -->
        <div class="relative p-4 rounded-xl bg-indigo-100 dark:bg-indigo-800 shadow text-indigo-700 dark:text-indigo-200 overflow-hidden">
            <p class="text-sm">Pesan Baru</p>
            <h3 class="text-2xl font-bold mt-1">5</h3>
            <i class="fas fa-envelope absolute top-2 right-2 text-4xl opacity-20"></i>
        </div>

        <!-- Bug / Error -->
        <div class="relative p-4 rounded-xl bg-red-100 dark:bg-red-800 shadow text-red-700 dark:text-red-200 overflow-hidden">
            <p class="text-sm">Bug / Error</p>
            <h3 class="text-2xl font-bold mt-1">2</h3>
            <i class="fas fa-bug absolute top-2 right-2 text-4xl opacity-20"></i>
        </div>

        <!-- Order Terkonfirmasi -->
        <div class="relative p-4 rounded-xl bg-green-100 dark:bg-green-800 shadow text-green-700 dark:text-green-200 overflow-hidden">
            <p class="text-sm">Order Terkonfirmasi</p>
            <h3 class="text-2xl font-bold mt-1">12</h3>
            <i class="fas fa-check-circle absolute top-2 right-2 text-4xl opacity-20"></i>
        </div>

    </div>

@endsection

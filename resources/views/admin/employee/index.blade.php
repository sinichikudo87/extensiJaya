@extends('layouts.admin')

@section('content')
    <div x-data x-init="$store.nav.setTitle('Employee Management')">
        <div class="w-full mx-auto bg-gray-900 p-6 rounded-2xl shadow-2xl text-white border border-gray-800">

            {{-- HEADER & SEARCH SECTION --}}
            <div class="flex flex-col md:flex-row items-center justify-between mb-4 pb-4 border-b border-gray-800 gap-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-users text-green-400 text-xl"></i>
                    <h6 class="text-base font-bold uppercase tracking-wider">Employee Store</h6>
                </div>

                <div class="flex flex-1 md:justify-end items-center gap-2">
                    {{-- Form Pencarian --}}
                    <form action="{{ url()->current() }}" method="GET" class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="Cari kode, nama, email, atau kota..."
                            class="w-full bg-gray-800 text-xs text-white border border-gray-700 rounded-full px-4 py-2 focus:outline-none focus:border-green-500 transition">
                        <button type="submit" class="absolute right-3 top-2 text-gray-400 hover:text-green-400">
                            <i class="fas fa-search text-xs"></i>
                        </button>
                    </form>

                    {{-- Tombol Add --}}
                    <button id="openModal"
                        class="text-white bg-green-600 hover:bg-green-700 w-8 h-8 rounded-full flex items-center justify-center shadow transition transform hover:scale-110"
                        title="Add Employee">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
            </div>

            {{-- DATA TABLE --}}
            <div class="w-full">
                {{-- 1. TAMPILAN MOBILE (Hanya muncul di layar Kecil < 768px) --}}
                <div class="block md:hidden space-y-4">
                    @forelse ($groupedEmployees as $city => $employees)
                        {{-- Header Kota untuk Mobile --}}
                        <div class="bg-emerald-900/20 p-2 rounded-lg border-l-4 border-emerald-500 my-4">
                            <span class="text-[10px] font-black text-emerald-400 uppercase">
                                <i class="fas fa-map-marked-alt mr-1"></i> {{ $city ?: 'CABANG LUAR KOTA' }}
                            </span>
                        </div>

                        @foreach ($employees as $emp)
                            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 shadow-md">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="text-emerald-400 font-mono text-[9px]">{{ $emp->employee_code }}</p>
                                        <h3 class="font-bold text-gray-100 uppercase text-sm">{{ $emp->name }}</h3>
                                    </div>
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase {{ $emp->status === 'active' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500' }}">
                                        {{ $emp->status }}
                                    </span>
                                </div>

                                <div class="text-[11px] text-gray-400 space-y-1 mb-4">
                                    <p><i class="fas fa-envelope mr-2 text-gray-600"></i>{{ $emp->email }}</p>
                                    <p><i class="fas fa-phone-alt mr-2 text-gray-600"></i>{{ $emp->phone }}</p>
                                    <p class="text-yellow-500 font-bold"><i class="fas fa-wallet mr-2 text-gray-600"></i>Rp
                                        {{ number_format($emp->salary, 0, ',', '.') }}</p>
                                </div>
                                @if(session()->get('role_id') == 1)
                                    <div class="flex gap-2 border-t border-gray-700 pt-3">
                                        {{-- Tombol Edit Mobile --}}
                                        <button type="button"
                                            class="flex-1 bg-gray-900 text-yellow-500 py-2 rounded-lg border border-gray-700 btn-edit-employee"
                                            data-id="{{ $emp->id }}" data-name="{{ $emp->name }}"
                                            data-code="{{ $emp->employee_code }}" data-email="{{ $emp->email }}"
                                            data-phone="{{ $emp->phone }}" data-salary="{{ $emp->salary }}"
                                            data-status="{{ $emp->status }}" data-city="{{ $emp->city }}"
                                            data-address="{{ $emp->address }}" data-join_date="{{ $emp->join_date }}"
                                            data-division_id="{{ $emp->division_id }}">
                                            <i class="fas fa-pencil-alt text-xs"></i>
                                        </button>

                                        {{-- Tombol Delete Mobile --}}
                                        <button
                                            class="flex-1 bg-gray-900 text-red-500 py-2 rounded-lg border border-gray-700 btn-delete-employee"
                                            data-id="{{ $emp->id }}" data-name="{{ $emp->name }}">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                @else
                                    <div class="flex gap-2 border-t border-gray-700 pt-3">
                                        <span class="text-gray-600 italic text-[10px]">No Actions</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @empty
                        <p class="text-center text-gray-500 py-10">Data tidak ditemukan</p>
                    @endforelse
                </div>

                {{-- 2. TAMPILAN DESKTOP (Hanya muncul di layar Medium ke atas >= 768px) --}}
                <div class="hidden md:block overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full bg-gray-800 text-xs text-white">
                        <thead class="bg-gray-700 text-gray-300 uppercase text-left font-medium">
                            <tr>
                                <th class="px-4 py-3 w-16">No</th>
                                <th class="px-4 py-3">Info Karyawan</th>
                                <th class="px-4 py-3">Kontak & Alamat</th>
                                <th class="px-4 py-3">Detail Kerja</th>
                                <th class="px-4 py-3 text-center">Gaji</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = $paginatedData->firstItem(); @endphp
                            @forelse ($groupedEmployees as $city => $employees)
                                <tr class="bg-emerald-900/10">
                                    <td colspan="7"
                                        class="px-6 py-3 font-black text-emerald-400 border-l-4 border-emerald-500 uppercase tracking-tighter">
                                        <i class="fas fa-map-marked-alt mr-2 text-[10px]"></i>
                                        {{ $city ?: 'CABANG LUAR KOTA' }}
                                    </td>
                                </tr>
                                @foreach ($employees as $emp)
                                    <tr class="hover:bg-gray-800/40 transition-colors group border-b border-gray-700/50">
                                        <td class="px-6 py-4 text-gray-500 font-mono italic">{{ $no++ }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-emerald-400 font-black text-[10px]">{{ $emp->employee_code }}</span>
                                                <span
                                                    class="font-bold text-gray-100 text-sm uppercase group-hover:text-emerald-300">{{ $emp->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                <span><i class="far fa-envelope text-gray-600 mr-1"></i>
                                                    {{ $emp->email }}</span>
                                                <span><i class="fas fa-phone-alt text-gray-600 mr-1"></i>
                                                    {{ $emp->phone }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="font-bold text-gray-200">{{ \Carbon\Carbon::parse($emp->join_date)->translatedFormat('d F Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-yellow-500 font-bold font-mono">Rp
                                                {{ number_format($emp->salary, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $emp->status === 'active' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-red-500/10 text-red-500 border border-red-500/20' }}">
                                                {{ $emp->status }}
                                            </span>
                                        </td>
                                        @if(session()->get('role_id') == 1)
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-center gap-2">

                                                    {{-- EDIT BUTTON --}}
                                                    <button
                                                        type="button"
                                                        class="group relative overflow-hidden
                                                            bg-gradient-to-br from-amber-500/10 to-yellow-500/5
                                                            hover:from-amber-500 hover:to-yellow-500
                                                            text-amber-400 hover:text-white
                                                            border border-amber-500/20 hover:border-amber-400/40
                                                            w-8 h-8 rounded-2xl
                                                            flex items-center justify-center
                                                            shadow-lg shadow-black/20
                                                            transition-all duration-300
                                                            hover:scale-105 active:scale-95
                                                            btn-edit-employee"

                                                        data-id="{{ $emp->id }}"
                                                        data-users_id="{{ $emp->users_id }}"
                                                        data-name="{{ $emp->name }}"
                                                        data-code="{{ $emp->employee_code }}"
                                                        data-email="{{ $emp->email }}"
                                                        data-phone="{{ $emp->phone }}"
                                                        data-salary="{{ $emp->salary }}"
                                                        data-status="{{ $emp->status }}"
                                                        data-city="{{ $emp->city }}"
                                                        data-address="{{ $emp->address }}"
                                                        data-join_date="{{ $emp->join_date }}"
                                                        data-division_id="{{ $emp->division_id }}"                                                        
                                                    >

                                                        {{-- Glow --}}
                                                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100
                                                                    bg-white/10 blur-xl transition-all duration-500"></div>

                                                        <i class="fas fa-pen text-xs relative z-10"></i>

                                                        {{-- Tooltip --}}
                                                        <span class="absolute -top-9 left-1/2 -translate-x-1/2
                                                                    bg-gray-950 text-white text-[10px]
                                                                    px-2 py-1 rounded-lg
                                                                    opacity-0 group-hover:opacity-100
                                                                    pointer-events-none transition-all duration-200
                                                                    whitespace-nowrap border border-gray-800">
                                                            Edit
                                                        </span>
                                                    </button>

                                                    {{-- DELETE BUTTON --}}
                                                    <button
                                                        type="button"
                                                        class="group relative overflow-hidden
                                                            bg-gradient-to-br from-red-500/10 to-rose-500/5
                                                            hover:from-red-600 hover:to-rose-600
                                                            text-red-400 hover:text-white
                                                            border border-red-500/20 hover:border-red-400/40
                                                            w-8 h-8 rounded-2xl
                                                            flex items-center justify-center
                                                            shadow-lg shadow-black/20
                                                            transition-all duration-300
                                                            hover:scale-105 active:scale-95
                                                            btn-delete-employee"

                                                        data-id="{{ $emp->id }}"
                                                        data-name="{{ $emp->name }}"
                                                    >

                                                        {{-- Glow --}}
                                                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100
                                                                    bg-white/10 blur-xl transition-all duration-500"></div>

                                                        <i class="fas fa-trash-alt text-xs relative z-10"></i>

                                                        {{-- Tooltip --}}
                                                        <span class="absolute -top-9 left-1/2 -translate-x-1/2
                                                                    bg-gray-950 text-white text-[10px]
                                                                    px-2 py-1 rounded-lg
                                                                    opacity-0 group-hover:opacity-100
                                                                    pointer-events-none transition-all duration-200
                                                                    whitespace-nowrap border border-gray-800">
                                                            Delete
                                                        </span>
                                                    </button>

                                                </div>
                                            </td>
                                        @else
                                            <td class="px-6 py-4 text-center space-x-2">
                                                <span class="text-gray-600 italic text-[10px]">No Actions</span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @empty
                                {{-- Kosong --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">
                    Menampilkan {{ $paginatedData->firstItem() }} s/d {{ $paginatedData->lastItem() }} dari
                    {{ $paginatedData->total() }} Karyawan
                </p>
                <div class="transform scale-90 md:scale-100">
                    {!! $paginatedData->appends(['search' => $search])->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.employee.modal')

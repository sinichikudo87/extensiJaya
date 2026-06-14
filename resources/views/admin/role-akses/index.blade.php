@extends('layouts.admin')

@section('content')
    <div x-data x-init="$store.nav.setTitle('Access Control & Role Management')">

        <style>
            .form-input-focus {
                transition: all 0.3s ease;
            }

            .form-input-focus:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
            }

            .permission-card {
                background: rgba(30, 41, 59, 0.3);
                border: 1px solid rgba(71, 85, 105, 0.5);
            }

            .permission-card:hover {
                border-color: #3b82f6;
                background: rgba(30, 41, 59, 0.6);
            }
        </style>

        <div
            class="w-full mx-auto bg-gray-900 p-3 md:p-6 rounded-2xl shadow-2xl text-white border border-gray-800 overflow-hidden">

            {{-- HEADER --}}
            <form action="#" method="POST">
                @csrf

                <div
                    class="flex flex-col lg:flex-row lg:items-center justify-between mb-6 md:mb-8 border-b border-gray-800 pb-5 gap-4">

                    {{-- LEFT --}}
                    <div class="flex items-start gap-3 md:gap-4 min-w-0">

                        <div
                            class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-blue-500/20 flex items-center justify-center border border-blue-500/30 shrink-0">
                            <i class="fas fa-shield-alt text-blue-400 text-lg md:text-2xl"></i>
                        </div>

                        <div class="min-w-0">
                            <h6
                                class="text-sm md:text-xl font-bold uppercase tracking-wide leading-tight text-blue-50 break-words">
                                Role Configuration
                            </h6>

                            <p
                                class="text-[8px] md:text-[10px] text-blue-500 uppercase mt-1 md:mt-2 tracking-[0.12em] md:tracking-[0.2em] font-black leading-relaxed">
                                Manajemen Hak Akses & Keamanan Sistem
                            </p>
                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

                        <div
                            class="flex items-center justify-between bg-gray-800 px-4 py-3 rounded-xl border border-gray-700 w-full sm:w-auto">

                            <span class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase">
                                Status Role
                            </span>

                            <label class="relative inline-flex items-center cursor-pointer ml-3">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>

                                <div
                                    class="w-11 h-6 bg-gray-700 rounded-full peer peer-checked:bg-blue-600
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:border after:rounded-full after:h-5 after:w-5
                                    after:transition-all peer-checked:after:translate-x-full">
                                </div>
                            </label>

                        </div>

                        <button type="button"
                            class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-5 md:px-6 py-3 rounded-xl text-[10px] md:text-xs font-black tracking-widest transition-all shadow-lg border border-blue-800/50">
                            SAVE PERMISSIONS
                        </button>

                    </div>

                </div>

                {{-- CONTENT --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 md:gap-8">

                    {{-- LEFT SIDE --}}
                    <div class="space-y-5 md:space-y-6">

                        {{-- SECURITY --}}
                        <div class="bg-gray-800/30 p-4 md:p-6 rounded-2xl border border-gray-800 shadow-inner">

                            <label
                                class="block text-blue-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                Security Overview
                            </label>

                            <div class="space-y-4">

                                <div>
                                    <label class="block text-gray-500 text-[9px] md:text-[10px] font-bold uppercase mb-2">
                                        Role Identifier
                                    </label>

                                    <input type="text" value="Super_Administrator"
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white outline-none form-input-focus">
                                </div>

                                <div>
                                    <label class="block text-gray-500 text-[9px] md:text-[10px] font-bold uppercase mb-2">
                                        Access Level (0-99)
                                    </label>

                                    <input type="number" value="99"
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-sm text-white outline-none form-input-focus">
                                </div>

                            </div>

                        </div>

                        {{-- IP --}}
                        <div class="bg-gray-800/30 p-4 md:p-6 rounded-2xl border border-gray-800">

                            <label
                                class="block text-rose-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-network-wired"></i>
                                IP Whitelist Restriction
                            </label>

                            <textarea rows="4"
                                class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-xs text-rose-100 outline-none focus:border-rose-500"
                                placeholder="e.g. 192.168.1.1, 10.0.0.5">127.0.0.1, 110.23.45.101</textarea>

                            <p class="text-[9px] text-gray-500 mt-2 leading-relaxed">
                                Pemisah koma untuk multi-IP. Kosongkan untuk akses publik.
                            </p>

                        </div>

                    </div>

                    {{-- RIGHT SIDE --}}
                    <div class="xl:col-span-2 space-y-5 md:space-y-6">

                        {{-- PERMISSIONS --}}
                        <div class="bg-gray-800/30 p-4 md:p-5 rounded-2xl border border-gray-800">

                            <label
                                class="block text-blue-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-5">
                                Module Permissions Matrix
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                @foreach ($menuData as $menu)

                                    <div class="permission-card p-4 rounded-2xl transition-all">

                                        {{-- TOP --}}
                                        <div class="flex justify-between items-start gap-3 mb-4">

                                            <div class="flex items-start gap-2 min-w-0">

                                                <div
                                                    class="w-2 h-2 rounded-full bg-blue-500 animate-pulse mt-1 shrink-0">
                                                </div>

                                                <span
                                                    class="text-[11px] md:text-xs font-bold text-gray-200 uppercase tracking-tight break-words">
                                                    {{ $menu->menu_title }}
                                                </span>

                                            </div>

                                            {{-- READ --}}
                                            <input type="checkbox"
                                                name="permissions[{{ $menu->menu_id }}][R]"
                                                value="1"
                                                {{ isset($existingPermissions[$menu->menu_id]) && $existingPermissions[$menu->menu_id]->can_read ? 'checked' : '' }}
                                                class="w-4 h-4 rounded border-gray-700 bg-gray-900 text-blue-600 focus:ring-blue-500 shrink-0">

                                        </div>

                                        {{-- ACTIONS --}}
                                        <div class="grid grid-cols-4 gap-2">

                                            @foreach (['C' => 'can_create', 'U' => 'can_update', 'D' => 'can_delete'] as $key => $col)

                                                <label
                                                    class="flex flex-col items-center justify-center p-2 md:p-3 bg-gray-900/50 rounded-xl cursor-pointer hover:bg-gray-800 border border-gray-700/50 transition-colors min-h-[70px]">

                                                    <input type="checkbox"
                                                        name="permissions[{{ $menu->menu_id }}][{{ $key }}]"
                                                        value="1"
                                                        class="sr-only peer"
                                                        {{ isset($existingPermissions[$menu->menu_id]) && $existingPermissions[$menu->menu_id]->$col ? 'checked' : '' }}>

                                                    <span
                                                        class="text-[10px] font-black text-gray-600 peer-checked:text-blue-400 transition-colors">
                                                        {{ $key }}
                                                    </span>

                                                    <span
                                                        class="text-[8px] text-center leading-tight text-gray-700 peer-checked:text-blue-900 font-bold uppercase mt-1">
                                                        {{ substr($col, 4) }}
                                                    </span>

                                                </label>

                                            @endforeach

                                            {{-- READ INFO --}}
                                            <div
                                                class="flex flex-col items-center justify-center p-2 md:p-3 bg-gray-900/10 rounded-xl opacity-40 min-h-[70px]">

                                                <span class="text-[10px] font-black text-gray-600 italic">
                                                    R
                                                </span>

                                                <span
                                                    class="text-[8px] text-gray-700 font-bold uppercase mt-1 text-center">
                                                    View
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                        {{-- TIME ACCESS --}}
                        <div class="bg-gray-800/30 p-4 md:p-5 rounded-2xl border border-gray-800">

                            <label
                                class="block text-indigo-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-4 italic">
                                <i class="fas fa-history mr-1"></i>
                                Time Access Restriction (Work Hours)
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- START --}}
                                <div
                                    class="flex items-center gap-3 bg-gray-900 p-4 rounded-2xl border border-gray-700">

                                    <div
                                        class="w-9 h-9 rounded-xl bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20 shrink-0">

                                        <i class="fas fa-sign-in-alt text-indigo-400 text-xs"></i>

                                    </div>

                                    <div class="flex-1 min-w-0">

                                        <label
                                            class="block text-[9px] text-gray-500 uppercase font-black">
                                            Login Allowed From
                                        </label>

                                        <input type="time" name="access_start"
                                            class="bg-transparent text-sm text-white outline-none w-full font-mono mt-1"
                                            value="{{ $role->access_start ?? '08:00' }}">

                                    </div>

                                </div>

                                {{-- END --}}
                                <div
                                    class="flex items-center gap-3 bg-gray-900 p-4 rounded-2xl border border-gray-700">

                                    <div
                                        class="w-9 h-9 rounded-xl bg-rose-500/10 flex items-center justify-center border border-rose-500/20 shrink-0">

                                        <i class="fas fa-sign-out-alt text-rose-400 text-xs"></i>

                                    </div>

                                    <div class="flex-1 min-w-0">

                                        <label
                                            class="block text-[9px] text-gray-500 uppercase font-black">
                                            Login Blocked After
                                        </label>

                                        <input type="time" name="access_end"
                                            class="bg-transparent text-sm text-white outline-none w-full font-mono mt-1"
                                            value="{{ $role->access_end ?? '18:00' }}">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
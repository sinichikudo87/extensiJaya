@extends('layouts.admin')

@section('content')
    <div x-data x-init="$store.nav.setTitle('System Identity Manager')">

        <form id="colorSettingsForm"
            action="{{ route('chart-color-setting.store') }}"
            method="POST"
            class="px-3 py-4 sm:px-0">

            @csrf

            <div
                class="w-full max-w-7xl mx-auto bg-gray-900 p-4 sm:p-6 lg:p-8 rounded-3xl shadow-2xl text-white border border-gray-800 overflow-hidden">

                {{-- HEADER --}}
                <div
                    class="flex flex-col xl:flex-row xl:items-center justify-between mb-6 md:mb-8 border-b border-gray-800 pb-6 md:pb-8 gap-5">

                    {{-- LEFT --}}
                    <div class="flex items-start gap-3 sm:gap-5 min-w-0">

                        <div
                            class="shrink-0 w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center border border-emerald-500/30 shadow-[0_0_20px_rgba(16,185,129,0.1)]">

                            <i class="fas fa-palette text-emerald-400 text-lg sm:text-2xl"></i>

                        </div>

                        <div class="min-w-0">

                            <h6
                                class="text-base sm:text-2xl font-black uppercase tracking-tight leading-tight text-white italic break-words">

                                Branding
                                <span class="text-emerald-500">Assets</span>

                            </h6>

                            <p
                                class="text-[8px] sm:text-[10px] text-gray-500 uppercase mt-2 tracking-[0.12em] sm:tracking-[0.2em] font-black leading-relaxed">

                                Visual Status & Identity Configuration

                            </p>

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <button type="submit"
                        class="w-full xl:w-auto bg-white hover:bg-emerald-500 text-gray-950 hover:text-white px-6 sm:px-10 py-4 xl:py-3 rounded-2xl text-[10px] font-black tracking-[0.15em] sm:tracking-[0.2em] transition-all shadow-xl active:scale-95 border border-white/10 uppercase flex items-center justify-center gap-3 group">

                        <i class="fas fa-check-circle text-sm"></i>

                        <span>Apply Changes</span>

                    </button>

                </div>

                {{-- GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6 lg:gap-8">

                    @foreach ($chartSettings as $index => $item)

                        @php
                            $styles = [
                                [
                                    'glow' => 'group-hover:border-emerald-500/50',
                                    'line' => 'focus:border-emerald-500',
                                    'tag' => 'Primary Marker',
                                ],
                                [
                                    'glow' => 'group-hover:border-blue-500/50',
                                    'line' => 'focus:border-blue-500',
                                    'tag' => 'Secondary Marker',
                                ],
                                [
                                    'glow' => 'group-hover:border-amber-500/50',
                                    'line' => 'focus:border-amber-500',
                                    'tag' => 'Final Marker',
                                ],
                            ];

                            $style = $styles[$index] ?? $styles[0];
                        @endphp

                        <div
                            class="group bg-gray-800/20 p-4 sm:p-6 rounded-3xl border border-gray-800 {{ $style['glow'] }} transition-all duration-500 overflow-hidden">

                            {{-- TOP --}}
                            <div class="flex justify-between items-start gap-3 mb-6 sm:mb-8">

                                <div class="space-y-2 min-w-0">

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full bg-gray-950 text-[8px] font-black text-gray-500 uppercase tracking-widest border border-white/5 italic">

                                        #0{{ $index + 1 }}

                                    </span>

                                    <h3
                                        class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] sm:tracking-[0.2em] break-words">

                                        {{ $style['tag'] }}

                                    </h3>

                                </div>

                                {{-- COLOR --}}
                                <div class="shrink-0">

                                    <input type="color"
                                        name="color_{{ $index + 1 }}"
                                        value="{{ $item->color }}"
                                        oninput="
                                            document.getElementById('hex-{{ $index }}').value = this.value;
                                            document.getElementById('hex-{{ $index }}').style.color = this.value
                                        "
                                        class="color-picker w-12 h-12 rounded-2xl cursor-pointer bg-transparent border-none">

                                </div>

                            </div>

                            {{-- FORM --}}
                            <div class="space-y-5 sm:space-y-6">

                                {{-- DESCRIPTION --}}
                                <div>

                                    <label
                                        class="text-[8px] sm:text-[9px] font-black text-gray-600 uppercase tracking-widest ml-1 mb-2 block italic">

                                        Label Description

                                    </label>

                                    <input type="text"
                                        name="description_{{ $index + 1 }}"
                                        value="{{ $item->description }}"
                                        class="w-full bg-gray-950/30 border-b-2 border-gray-800 px-1 py-3 text-sm font-bold text-white outline-none {{ $style['line'] }}">

                                </div>

                                {{-- HEX --}}
                                <div
                                    class="flex items-center justify-between gap-3 bg-black/40 p-3 rounded-2xl border border-white/5 shadow-inner overflow-hidden">

                                    <span
                                        class="text-[8px] sm:text-[9px] font-black text-gray-700 uppercase tracking-tight shrink-0">

                                        Hex Code

                                    </span>

                                    <input type="text"
                                        readonly
                                        id="hex-{{ $index }}"
                                        value="{{ $item->color }}"
                                        class="bg-transparent border-none p-0 text-[10px] sm:text-[11px] font-bold font-mono text-right w-24 sm:w-28 uppercase tracking-wider truncate"
                                        style="color: {{ $item->color }}">

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </form>

    </div>

    <style>
        .color-picker::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        .color-picker::-webkit-color-swatch {
            border: none;
            border-radius: 1rem;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
    </style>
@endsection
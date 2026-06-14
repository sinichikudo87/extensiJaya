@extends('layouts.admin')

@section('content')
    <div x-data x-init="$store.nav.setTitle('Company Profile Management')">

        <style>
            * {
                min-width: 0;
            }

            html,
            body {
                overflow-x: hidden;
            }

            .form-input-focus {
                transition: all 0.3s ease;
            }

            .form-input-focus:focus {
                border-color: #10b981;
                box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
            }
        </style>

        <div
            class="w-full max-w-full mx-auto
                bg-gray-900
                p-3 md:p-6
                rounded-2xl shadow-2xl
                text-white border border-gray-800
                overflow-hidden">

            {{-- HEADER & ACTION --}}
            <form id="companyProfileForm" action="{{ route('company-profile.store', $company->id) }}" method="POST"
                enctype="multipart/form-data">

                @csrf

                {{-- HEADER --}}
                <div
                    class="flex flex-col lg:flex-row
           lg:items-center lg:justify-between
           gap-5
           mb-6 pb-5
           border-b border-gray-800/80
           w-full min-w-0">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-4 min-w-0 flex-1">

                        {{-- ICON --}}
                        <div
                            class="w-11 h-11 rounded-2xl
                   bg-gradient-to-br
                   from-emerald-500/20 to-teal-500/10
                   border border-emerald-500/20
                   flex items-center justify-center
                   shadow-lg shadow-emerald-900/20
                   shrink-0">

                            <i class="fas fa-building text-emerald-400 text-lg"></i>

                        </div>

                        {{-- TITLE --}}
                        <div class="min-w-0">

                            <h6
                                class="text-sm md:text-[15px]
                       font-black
                       uppercase
                       tracking-[0.18em]
                       text-white
                       truncate">

                                Master Profil Perusahaan

                            </h6>

                            <p
                                class="text-[9px]
                       text-gray-500
                       uppercase
                       tracking-[0.22em]
                       font-bold
                       mt-1 truncate">

                                Data Identitas & Operasional Perusahaan

                            </p>

                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div
                        class="flex flex-col sm:flex-row
               items-stretch sm:items-center
               gap-3
               w-full lg:w-auto
               shrink-0">

                        {{-- STATUS --}}
                        <div
                            class="flex items-center justify-between
                   gap-4
                   bg-gray-800/60
                   border border-gray-700/70
                   rounded-2xl
                   px-4 py-2.5
                   backdrop-blur-sm">

                            <div class="flex flex-col leading-none">

                                <span
                                    class="text-[8px]
                           uppercase
                           tracking-[0.2em]
                           text-gray-500
                           font-black">

                                    System

                                </span>

                                <span
                                    class="text-[10px]
                           uppercase
                           tracking-[0.15em]
                           text-emerald-400
                           font-bold mt-1">

                                    Active Status

                                </span>

                            </div>

                            <label class="relative inline-flex items-center cursor-pointer shrink-0">

                                <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                    {{ $company->is_active ? 'checked' : '' }}>

                                <div
                                    class="w-11 h-6 bg-gray-700
                           rounded-full
                           peer-focus:outline-none
                           peer-checked:bg-emerald-600
                           after:content-['']
                           after:absolute
                           after:top-[2px]
                           after:left-[2px]
                           after:bg-white
                           after:border after:border-gray-300
                           after:rounded-full
                           after:h-5 after:w-5
                           after:transition-all
                           peer-checked:after:translate-x-full">
                                </div>

                            </label>

                        </div>

                        {{-- BUTTON --}}
                        <button type="submit"
                            class="group
                   relative overflow-hidden
                   bg-gradient-to-r
                   from-emerald-600 to-teal-700
                   hover:from-emerald-500
                   hover:to-teal-600
                   text-white
                   px-6 py-3
                   rounded-2xl
                   text-[10px]
                   font-black
                   tracking-[0.25em]
                   transition-all duration-300
                   shadow-lg shadow-emerald-900/30
                   active:scale-95
                   border border-emerald-500/20
                   whitespace-nowrap">

                            <span class="relative z-10 flex items-center justify-center gap-2">

                                <i class="fas fa-save text-[11px]"></i>

                                UPDATE DATA

                            </span>

                            <div
                                class="absolute inset-0
                       opacity-0 group-hover:opacity-100
                       transition-opacity
                       bg-white/10">
                            </div>

                        </button>

                    </div>

                </div>

                {{-- CONTENT --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 w-full min-w-0">

                    {{-- LEFT --}}
                    <div class="space-y-6 min-w-0">

                        {{-- LOGO --}}
                        <div
                            class="bg-gray-800/30
                               p-4 md:p-6
                               rounded-2xl
                               border border-gray-800
                               text-center
                               overflow-hidden">

                            <label
                                class="block text-emerald-400
                                   text-[10px]
                                   font-black uppercase
                                   tracking-widest mb-4">

                                Logo Perusahaan

                            </label>

                            <div id="logoContainer"
                                class="relative group
                                   mx-auto
                                   w-32 h-32 md:w-40 md:h-40
                                   mb-4
                                   bg-gray-900
                                   rounded-2xl
                                   flex items-center justify-center
                                   overflow-hidden
                                   border border-gray-700
                                   shadow-inner">

                                @if ($company->logo)
                                    <img id="preview" src="{{ asset('images/' . $company->logo) }}"
                                        class="w-full h-full object-contain block mx-auto">
                                @else
                                    <i id="placeholder" class="fas fa-warehouse text-gray-700 text-4xl md:text-5xl">
                                    </i>
                                @endif

                                <label
                                    class="absolute inset-0
                                       flex items-center justify-center
                                       bg-emerald-600/60
                                       opacity-0 group-hover:opacity-100
                                       rounded-2xl
                                       cursor-pointer
                                       transition-opacity
                                       text-white text-[10px] md:text-xs
                                       font-bold z-10 text-center p-2">

                                    <span>
                                        <i class="fas fa-camera mr-2"></i>
                                        Ganti Logo
                                    </span>

                                    <input type="file" name="logo" id="logoInput" class="hidden" accept="image/*">

                                </label>

                            </div>

                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="lg:col-span-2 space-y-6 min-w-0">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full min-w-0">

                            {{-- IDENTITAS --}}
                            <div
                                class="md:col-span-2
                                   bg-gray-800/30
                                   p-4 rounded-xl
                                   border border-gray-800
                                   grid grid-cols-1 md:grid-cols-2
                                   gap-4
                                   min-w-0">

                                <div class="min-w-0">

                                    <label
                                        class="block text-emerald-400
                                           text-[10px]
                                           font-black uppercase
                                           tracking-widest mb-1">

                                        Nama Perusahaan (`name`)

                                    </label>

                                    <input type="text" name="name" value="{{ $company->name }}"
                                        class="w-full max-w-full
                                           bg-gray-800
                                           border border-gray-700
                                           rounded-lg
                                           px-4 py-2.5
                                           text-sm text-white
                                           outline-none
                                           form-input-focus">

                                </div>

                                <div class="min-w-0">

                                    <label
                                        class="block text-emerald-400
                                           text-[10px]
                                           font-black uppercase
                                           tracking-widest mb-1">

                                        Nama Alias (`alias_name`)

                                    </label>

                                    <input type="text" name="alias_name" value="{{ $company->alias_name }}"
                                        class="w-full max-w-full
                                           bg-gray-800
                                           border border-gray-700
                                           rounded-lg
                                           px-4 py-2.5
                                           text-sm text-white
                                           outline-none
                                           form-input-focus">

                                </div>

                            </div>

                            {{-- LEGAL --}}
                            <div class="min-w-0">

                                <label
                                    class="block text-gray-400
                                       text-[10px]
                                       font-bold uppercase mb-1">

                                    NPWP (`npwp`)

                                </label>

                                <input type="text" name="npwp" value="{{ $company->npwp }}"
                                    class="w-full max-w-full
                                       bg-gray-800
                                       border border-gray-700
                                       rounded-lg
                                       px-4 py-2.5
                                       text-sm text-white
                                       outline-none
                                       form-input-focus">

                            </div>

                            <div class="min-w-0">

                                <label
                                    class="block text-gray-400
                                       text-[10px]
                                       font-bold uppercase mb-1">

                                    No. Izin / Permit (`permit_number`)

                                </label>

                                <input type="text" name="permit_number" value="{{ $company->permit_number }}"
                                    class="w-full max-w-full
                                       bg-gray-800
                                       border border-gray-700
                                       rounded-lg
                                       px-4 py-2.5
                                       text-sm text-white
                                       outline-none
                                       form-input-focus">

                            </div>

                            {{-- KONTAK --}}
                            <div
                                class="md:col-span-2
                                    grid grid-cols-1 md:grid-cols-2
                                    gap-4 min-w-0">

                                {{-- OFFICE --}}
                                <div
                                    class="bg-gray-800/30
                                       p-4 rounded-xl
                                       border border-gray-800
                                       min-w-0">

                                    <label
                                        class="block text-teal-400
                                           text-[10px]
                                           font-black uppercase
                                           mb-3 border-b border-teal-900
                                           pb-1">

                                        Kontak Kantor

                                    </label>

                                    <div class="space-y-3">

                                        <input type="email" name="email" value="{{ $company->email }}"
                                            placeholder="Email"
                                            class="w-full max-w-full
                                               bg-gray-900
                                               border border-gray-700
                                               rounded-lg
                                               px-3 py-2
                                               text-xs text-white
                                               outline-none
                                               focus:border-emerald-500">

                                        <input type="text" name="phone" value="{{ $company->phone }}"
                                            placeholder="Phone"
                                            class="w-full max-w-full
                                               bg-gray-900
                                               border border-gray-700
                                               rounded-lg
                                               px-3 py-2
                                               text-xs text-white
                                               outline-none
                                               focus:border-emerald-500">

                                    </div>

                                </div>

                                {{-- OWNER --}}
                                <div
                                    class="bg-gray-800/30
                                       p-4 rounded-xl
                                       border border-gray-800
                                       min-w-0">

                                    <label
                                        class="block text-teal-400
                                           text-[10px]
                                           font-black uppercase
                                           mb-3 border-b border-teal-900
                                           pb-1">

                                        Kontak Owner

                                    </label>

                                    <div class="space-y-3">

                                        <input type="text" name="owner_name" value="{{ $company->owner_name }}"
                                            placeholder="Owner Name"
                                            class="w-full max-w-full
                                               bg-gray-900
                                               border border-gray-700
                                               rounded-lg
                                               px-3 py-2
                                               text-xs text-white
                                               outline-none
                                               focus:border-emerald-500">

                                        <input type="text" name="owner_phone" value="{{ $company->owner_phone }}"
                                            placeholder="Owner Phone"
                                            class="w-full max-w-full
                                               bg-gray-900
                                               border border-gray-700
                                               rounded-lg
                                               px-3 py-2
                                               text-xs text-white
                                               outline-none
                                               focus:border-emerald-500">

                                    </div>

                                </div>

                            </div>

                            {{-- LOKASI --}}
                            <div
                                class="md:col-span-2
                                   bg-gray-800/30
                                   p-4 rounded-xl
                                   border border-gray-800
                                   min-w-0">

                                <label
                                    class="block text-emerald-400
                                       text-[10px]
                                       font-black uppercase
                                       tracking-widest mb-3">

                                    Lokasi Operasional

                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 min-w-0">

                                    <div class="md:col-span-2 min-w-0">

                                        <input type="text" name="city" value="{{ $company->city }}"
                                            placeholder="Kota (`city`)"
                                            class="w-full max-w-full
                                               bg-gray-900
                                               border border-gray-700
                                               rounded-lg
                                               px-3 py-2
                                               text-xs text-white
                                               outline-none
                                               focus:border-emerald-500">

                                    </div>

                                    <div
                                        class="flex items-center gap-2
                                           bg-gray-900
                                           border border-gray-700
                                           rounded-lg
                                           px-3 py-2
                                           min-w-0">

                                        <span
                                            class="text-[10px]
                                                 text-gray-500
                                                 font-bold uppercase
                                                 shrink-0">

                                            PPN:

                                        </span>

                                        <select name="ppn"
                                            class="bg-transparent
                                               text-xs text-emerald-400
                                               font-bold
                                               outline-none
                                               flex-1
                                               min-w-0">

                                            <option value="0" {{ $company->ppn == 0 ? 'selected' : '' }}>
                                                No PPN (0%)
                                            </option>

                                            <option value="1" {{ $company->ppn == 1 ? 'selected' : '' }}>
                                                Include PPN (12%)
                                            </option>

                                        </select>

                                    </div>

                                </div>

                                <textarea name="address" rows="2" placeholder="Alamat Lengkap (`address`)"
                                    class="w-full max-w-full
                                       bg-gray-900
                                       border border-gray-700
                                       rounded-lg
                                       px-3 py-2
                                       text-xs text-white
                                       outline-none
                                       focus:border-emerald-500
                                       mb-4 resize-none">{{ $company->address }}</textarea>

                                {{-- COORDINATE --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 min-w-0">

                                    <input type="text" name="latitude" value="{{ $company->latitude }}"
                                        placeholder="Latitude"
                                        class="w-full max-w-full
                                           bg-gray-900
                                           border border-gray-700
                                           rounded-lg
                                           px-3 py-2
                                           text-[10px] text-white
                                           outline-none
                                           focus:border-emerald-500">

                                    <input type="text" name="longitude" value="{{ $company->longitude }}"
                                        placeholder="Longitude"
                                        class="w-full max-w-full
                                           bg-gray-900
                                           border border-gray-700
                                           rounded-lg
                                           px-3 py-2
                                           text-[10px] text-white
                                           outline-none
                                           focus:border-emerald-500">

                                    <div class="relative min-w-0">

                                        <input type="number" name="coverage" value="{{ $company->coverage }}"
                                            placeholder="Coverage (Km)"
                                            class="w-full max-w-full
                                               bg-gray-900
                                               border border-gray-700
                                               rounded-lg
                                               px-3 py-2
                                               pr-10
                                               text-[10px] text-white
                                               outline-none
                                               focus:border-emerald-500">

                                        <span
                                            class="absolute right-3 top-2
                                               text-[9px]
                                               text-gray-500
                                               uppercase font-bold">

                                            KM

                                        </span>

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

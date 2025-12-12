@extends('layouts.admin')

@section('title', 'Live GPS Map')

@section('content')

    <div class="w-full h-[600px] rounded-xl shadow-lg overflow-hidden relative z-0">
        <div id="map" class="w-full h-full"></div>
    </div>
    <!-- Bisa di bawah peta atau di samping peta -->
    <div id="marker-panel" class="fixed bottom-4 right-4 w-72 max-h-64 bg-black/70 text-white p-4 rounded-lg shadow-lg overflow-auto hidden z-50">
        <!-- Konten marker akan dimasukkan JS -->
    </div>

@endsection
@vite(['resources/js/maps/index.js'])

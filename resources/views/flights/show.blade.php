@extends('layouts.app')

@section('title', 'Rincian Tiket ' . $flight->flight_code . ' - Pemesanan Pesawat')

@section('content')
<div class="mb-6">
    <a href="{{ route('flights.index') }}" class="text-sm text-sky-400 hover:underline">
        ← Kembali ke Katalog Tiket
    </a>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-sky-500/20 via-slate-900 to-slate-900 border-b border-slate-800 p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-3 py-1 rounded-lg text-xs font-bold bg-sky-500 text-slate-950">
                    {{ $flight->flight_code }}
                </span>
                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-800 text-slate-200">
                    Kelas {{ $flight->seat_class }}
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-white">{{ $flight->airline }}</h1>
            <p class="text-sm text-slate-400 mt-1">Jadwal Keberangkatan: {{ $flight->flight_date->translatedFormat('l, d F Y') }}</p>
        </div>

        <div class="md:text-right">
            <p class="text-xs text-slate-400">Tarif Tiket</p>
            <p class="text-3xl font-extrabold text-sky-400">Rp {{ number_format($flight->price, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400">Sudah termasuk pajak bandara</p>
        </div>
    </div>

    <!-- Detail Jadwal & Fasilitas -->
    <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Timeline Rute -->
        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-sky-400 mb-5">Detail Rute Penerbangan</h2>

            <div class="relative pl-6 border-l-2 border-sky-500/40 space-y-8">
                <div class="relative">
                    <span class="w-3.5 h-3.5 rounded-full bg-sky-400 absolute -left-[31px] top-1"></span>
                    <p class="text-xs text-slate-400">Keberangkatan ({{ substr($flight->departure_time, 0, 5) }} WITA/WIB)</p>
                    <p class="text-lg font-bold text-white">{{ $flight->origin }}</p>
                </div>

                <div class="bg-slate-900/90 border border-slate-800 rounded-xl p-3 text-xs text-slate-300">
                    Penerbangan Langsung &bull; Armada <strong>{{ $flight->aircraft_type }}</strong>
                </div>

                <div class="relative">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-400 absolute -left-[31px] top-1"></span>
                    <p class="text-xs text-slate-400">Kedatangan ({{ substr($flight->arrival_time, 0, 5) }} Waktu Setempat)</p>
                    <p class="text-lg font-bold text-white">{{ $flight->destination }}</p>
                </div>
            </div>
        </div>

        <!-- Spesifikasi & Kuota -->
        <div class="space-y-4">
            <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6">
                <h2 class="text-sm font-bold uppercase tracking-wider text-sky-400 mb-4">Fasilitas & Ketersediaan</h2>
                <ul class="space-y-3 text-sm text-slate-300">
                    <li class="flex justify-between border-b border-slate-800/80 pb-2">
                        <span class="text-slate-400">Bagasi & Kabin</span>
                        <span class="font-medium text-white">{{ $flight->baggage_info }}</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-800/80 pb-2">
                        <span class="text-slate-400">Tipe Pesawat</span>
                        <span class="font-medium text-white">{{ $flight->aircraft_type }}</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-800/80 pb-2">
                        <span class="text-slate-400">Pemilihan Kursi</span>
                        <span class="font-medium text-emerald-400">Gratis Pilih Kursi Kabin</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-slate-400">Sisa Kuota Kursi</span>
                        <span class="font-bold text-sky-400">{{ $flight->available_seats }} dari {{ $flight->total_seats }} Kursi</span>
                    </li>
                </ul>
            </div>

            <!-- Tombol Lanjut ke Halaman 3 -->
            @if($flight->available_seats > 0)
                <a href="{{ route('flights.booking', $flight) }}"
                    class="w-full block text-center py-3.5 px-6 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-extrabold text-base transition shadow-lg shadow-sky-500/20">
                    Lanjut Pilih Kuota & Kursi →
                </a>
            @else
                <button disabled class="w-full py-3.5 px-6 rounded-xl bg-slate-800 text-slate-500 font-bold cursor-not-allowed">
                    Kuota Tiket Habis
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
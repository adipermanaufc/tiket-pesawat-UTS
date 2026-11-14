@extends('layouts.app')

@section('title', 'Katalog Tiket - Pemesanan Pesawat')

@section('content')
<!-- Header & Form Pencarian -->
<div class="bg-gradient-to-br from-slate-900 via-slate-900 to-sky-950/50 border border-slate-800 rounded-2xl p-6 md:p-8 mb-8">
    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-sky-500/15 text-sky-400 border border-sky-500/30 mb-3">
        Penerbangan Domestik Terjadwal
    </span>
    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Cari & Pesan Tiket Pesawat</h1>
    <p class="text-slate-400 text-sm mb-6">Pilih jadwal penerbangan terbaik, tentukan kursi kabin Anda, dan dapatkan E-Ticket secara instan.</p>

    <form action="{{ route('flights.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5">Kota Asal</label>
            <input type="text" name="origin" value="{{ request('origin') }}" placeholder="Contoh: Banjarmasin"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-sky-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5">Kota Tujuan</label>
            <input type="text" name="destination" value="{{ request('destination') }}" placeholder="Contoh: Jakarta"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-sky-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5">Kelas Kabin</label>
            <select name="seat_class"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-sky-400 focus:outline-none">
                <option value="">Semua Kelas</option>
                <option value="Ekonomi" {{ request('seat_class') == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                <option value="Bisnis" {{ request('seat_class') == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit"
                class="w-full py-2.5 px-5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-sm transition">
                Cari Tiket
            </button>
            @if(request()->hasAny(['origin', 'destination', 'seat_class']))
                <a href="{{ route('flights.index') }}"
                    class="py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Daftar Katalog Penerbangan -->
<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-white">Jadwal Penerbangan Tersedia ({{ $flights->count() }})</h2>
</div>

<div class="space-y-4">
    @forelse($flights as $flight)
        <div class="bg-slate-900 border border-slate-800 hover:border-sky-500/50 rounded-2xl p-6 transition flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Info Maskapai -->
            <div class="lg:w-1/4">
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-sky-500/15 text-sky-400">
                        {{ $flight->flight_code }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-medium {{ $flight->seat_class === 'Bisnis' ? 'bg-amber-500/15 text-amber-300' : 'bg-slate-800 text-slate-300' }}">
                        {{ $flight->seat_class }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-white">{{ $flight->airline }}</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $flight->flight_date->translatedFormat('d F Y') }}</p>
            </div>

            <!-- Rute & Jam Terbang -->
            <div class="lg:w-2/4 flex items-center justify-between bg-slate-950/70 border border-slate-800/80 rounded-xl px-5 py-4">
                <div>
                    <p class="text-xl font-bold text-white">{{ substr($flight->departure_time, 0, 5) }}</p>
                    <p class="text-xs text-sky-400 font-medium">{{ $flight->origin }}</p>
                </div>

                <div class="flex flex-col items-center px-4">
                    <span class="text-[11px] text-slate-400 mb-1">Langsung</span>
                    <div class="w-24 md:w-32 h-[2px] bg-slate-700 relative flex items-center justify-center">
                        <span class="bg-slate-950 px-1.5 text-sky-400 text-xs">✈</span>
                    </div>
                    <span class="text-[11px] text-slate-500 mt-1">{{ $flight->aircraft_type }}</span>
                </div>

                <div class="text-right">
                    <p class="text-xl font-bold text-white">{{ substr($flight->arrival_time, 0, 5) }}</p>
                    <p class="text-xs text-sky-400 font-medium">{{ $flight->destination }}</p>
                </div>
            </div>

            <!-- Harga & Tombol Aksi -->
            <div class="lg:w-1/4 flex lg:flex-col items-center lg:items-end justify-between border-t lg:border-t-0 pt-4 lg:pt-0 border-slate-800">
                <div class="lg:text-right">
                    <p class="text-xs text-slate-400">Harga per penumpang</p>
                    <p class="text-xl font-extrabold text-sky-400">
                        Rp {{ number_format($flight->price, 0, ',', '.') }}
                    </p>
                    <p class="text-xs mt-1 {{ $flight->available_seats > 5 ? 'text-emerald-400' : 'text-amber-400' }}">
                        Sisa kuota: <strong>{{ $flight->available_seats }}</strong> / {{ $flight->total_seats }} kursi
                    </p>
                </div>

                <a href="{{ route('flights.show', $flight) }}"
                    class="mt-0 lg:mt-3 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-bold text-sm transition">
                    Lihat Rincian →
                </a>
            </div>
        </div>
    @empty
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-10 text-center">
            <p class="text-slate-400">Tidak ada jadwal penerbangan yang sesuai dengan filter pencarian.</p>
        </div>
    @endforelse
</div>
@endsection
@extends('layouts.app')

@section('title', 'E-Ticket ' . $booking->booking_code . ' - Pemesanan Pesawat')

@section('content')
@if(session('success'))
    <div class="no-print mb-6 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/40 text-emerald-300 text-sm flex items-center justify-between">
        <span>✓ {{ session('success') }}</span>
        <span class="font-bold">Kode Booking: {{ $booking->booking_code }}</span>
    </div>
@endif

<!-- Kartu E-Ticket / Boarding Pass -->
<div class="max-w-3xl mx-auto bg-slate-900 border border-slate-700 rounded-3xl overflow-hidden shadow-2xl">
    <!-- Top Header -->
    <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-6 py-5 text-slate-950 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest opacity-80">E-Ticket / Bukti Pemesanan Resmi</p>
            <h1 class="text-2xl font-black tracking-tight">{{ $booking->flight->airline }}</h1>
        </div>
        <div class="sm:text-right">
            <p class="text-xs font-bold uppercase opacity-80">Kode Booking (PNR)</p>
            <p class="text-2xl font-mono font-black tracking-wider">{{ $booking->booking_code }}</p>
        </div>
    </div>

    <!-- Body E-Ticket -->
    <div class="p-6 md:p-8 space-y-6">
        <!-- Info Rute -->
        <div class="grid grid-cols-3 items-center bg-slate-950 border border-slate-800 rounded-2xl p-5">
            <div>
                <p class="text-xs text-slate-400">Keberangkatan</p>
                <p class="text-2xl font-extrabold text-white mt-0.5">{{ substr($booking->flight->departure_time, 0, 5) }}</p>
                <p class="text-sm font-semibold text-sky-400">{{ $booking->flight->origin }}</p>
            </div>

            <div class="text-center">
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-500/15 text-sky-400 border border-sky-500/30">
                    {{ $booking->flight->flight_code }}
                </span>
                <p class="text-xs text-slate-400 mt-2">{{ $booking->flight->flight_date->translatedFormat('d M Y') }}</p>
            </div>

            <div class="text-right">
                <p class="text-xs text-slate-400">Kedatangan</p>
                <p class="text-2xl font-extrabold text-white mt-0.5">{{ substr($booking->flight->arrival_time, 0, 5) }}</p>
                <p class="text-sm font-semibold text-sky-400">{{ $booking->flight->destination }}</p>
            </div>
        </div>

        <!-- Detail Penumpang & Kursi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="space-y-3 bg-slate-950/60 border border-slate-800 rounded-2xl p-5">
                <h2 class="text-xs font-bold uppercase tracking-wider text-sky-400">Data Pemesan / Penumpang</h2>
                <div>
                    <p class="text-xs text-slate-400">Nama Lengkap</p>
                    <p class="font-bold text-white text-base">{{ $booking->passenger_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">NIK / Nomor Identitas</p>
                    <p class="font-medium text-slate-200">{{ $booking->passenger_nik }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Kontak</p>
                    <p class="text-slate-300">{{ $booking->passenger_email }} &bull; {{ $booking->passenger_phone }}</p>
                </div>
            </div>

            <div class="space-y-3 bg-slate-950/60 border border-slate-800 rounded-2xl p-5">
                <h2 class="text-xs font-bold uppercase tracking-wider text-sky-400">Detail Kursi & Pembayaran</h2>
                <div class="flex justify-between">
                    <span class="text-slate-400">Kelas Kabin</span>
                    <span class="font-semibold text-white">{{ $booking->flight->seat_class }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Jumlah Tiket</span>
                    <span class="font-semibold text-white">{{ $booking->ticket_qty }} Penumpang</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Nomor Kursi</span>
                    <span class="font-extrabold text-sky-400">{{ $booking->selected_seats }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-800">
                    <span class="text-slate-400">Total Dibayar</span>
                    <span class="font-extrabold text-emerald-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Catatan Boarding -->
        <div class="border-t border-dashed border-slate-700 pt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs text-slate-400">
            <div>
                <p>Status Tiket: <span class="text-emerald-400 font-bold">{{ $booking->status }}</span></p>
                <p class="mt-0.5">Harap tiba di bandara minimal 90 menit sebelum jadwal keberangkatan.</p>
            </div>
            <div class="font-mono text-slate-500 text-right">
                ISSUED: {{ $booking->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Tombol Aksi -->
<div class="no-print max-w-3xl mx-auto mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <a href="{{ route('flights.index') }}"
        class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-sm transition">
        ← Pesan Tiket Lainnya
    </a>

    <button onclick="window.print()"
        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-extrabold text-sm transition">
        🖨 Cetak / Simpan PDF E-Ticket
    </button>
</div>
@endsection
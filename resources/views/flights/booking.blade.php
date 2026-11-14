@extends('layouts.app')

@section('title', 'Pilih Kursi & Data Penumpang - ' . $flight->flight_code)

@section('content')
<div class="mb-6">
    <a href="{{ route('flights.show', $flight) }}" class="text-sm text-sky-400 hover:underline">
        ← Kembali ke Rincian Tiket
    </a>
</div>

@if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-500/15 border border-red-500/40 text-red-300 text-sm">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('flights.store', $flight) }}" method="POST" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    @csrf

    <!-- Kolom Kiri: Interaktif Kuota & Denah Kursi Pesawat -->
    <div class="lg:col-span-6 bg-slate-900 border border-slate-800 rounded-2xl p-6">
        <h2 class="text-lg font-bold text-white mb-1">1. Pilih Kuota & Kursi Kabin</h2>
        <p class="text-xs text-slate-400 mb-5">Tentukan jumlah tiket, lalu klik kursi yang tersedia pada denah kabin.</p>

        <!-- Input Jumlah Kuota Tiket -->
        <div class="mb-6">
            <label for="ticket_qty" class="block text-xs font-semibold text-slate-300 mb-2">
                Jumlah Kuota Tiket (Maks. {{ min(5, $flight->available_seats) }} tiket per transaksi)
            </label>
            <select name="ticket_qty" id="ticket_qty"
                class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-sky-400 focus:outline-none">
                @for($i = 1; $i <= min(5, $flight->available_seats); $i++)
                    <option value="{{ $i }}" {{ old('ticket_qty', 1) == $i ? 'selected' : '' }}>
                        {{ $i }} Penumpang (Rp {{ number_format($i * $flight->price, 0, ',', '.') }})
                    </option>
                @endfor
            </select>
        </div>

        <!-- Legenda Status Kursi -->
        <div class="flex flex-wrap items-center gap-4 text-xs mb-5 bg-slate-950 p-3 rounded-xl border border-slate-800">
            <div class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded bg-slate-800 border border-slate-600 inline-block"></span>
                <span class="text-slate-300">Tersedia</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded bg-sky-500 inline-block"></span>
                <span class="text-slate-300">Dipilih</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded bg-red-500/30 border border-red-500/50 inline-block"></span>
                <span class="text-slate-400">Terisi</span>
            </div>
        </div>

        <!-- Denah Kursi Kabin (5 Baris x 6 Kolom = 30 Kursi) -->
        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5">
            <div class="text-center text-xs font-bold text-slate-500 tracking-widest uppercase mb-4 pb-2 border-b border-slate-800">
                ▲ Bagian Depan Pesawat (Kokpit) ▲
            </div>

            <div class="space-y-2.5 max-w-xs mx-auto">
                @for($row = 1; $row <= 5; $row++)
                    <div class="flex items-center justify-between gap-2">
                        <!-- Sisi Kiri: A, B, C -->
                        <div class="flex gap-1.5">
                            @foreach(['A', 'B', 'C'] as $col)
                                @php
                                    $seatCode = $row . $col;
                                    $isBooked = in_array($seatCode, $bookedSeats);
                                @endphp
                                <button type="button"
                                    data-seat="{{ $seatCode }}"
                                    {{ $isBooked ? 'disabled' : '' }}
                                    class="seat-btn w-10 h-10 rounded-lg text-xs font-bold transition
                                    {{ $isBooked
                                        ? 'bg-red-500/20 border border-red-500/40 text-red-400/60 cursor-not-allowed'
                                        : 'bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200' }}">
                                    {{ $seatCode }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Lorong Kabin -->
                        <span class="text-[11px] font-bold text-slate-600">{{ $row }}</span>

                        <!-- Sisi Kanan: D, E, F -->
                        <div class="flex gap-1.5">
                            @foreach(['D', 'E', 'F'] as $col)
                                @php
                                    $seatCode = $row . $col;
                                    $isBooked = in_array($seatCode, $bookedSeats);
                                @endphp
                                <button type="button"
                                    data-seat="{{ $seatCode }}"
                                    {{ $isBooked ? 'disabled' : '' }}
                                    class="seat-btn w-10 h-10 rounded-lg text-xs font-bold transition
                                    {{ $isBooked
                                        ? 'bg-red-500/20 border border-red-500/40 text-red-400/60 cursor-not-allowed'
                                        : 'bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200' }}">
                                    {{ $seatCode }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <input type="hidden" name="selected_seats" id="selected_seats" value="{{ old('selected_seats') }}">
    </div>

    <!-- Kolom Kanan: Form Pengisian Data Pemesan & Ringkasan Biaya -->
    <div class="lg:col-span-6 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-1">2. Pengisian Data Pemesan</h2>
            <p class="text-xs text-slate-400 mb-5">Pastikan data sesuai dengan KTP / Paspor yang berlaku.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs text-slate-300 mb-1.5">Nama Lengkap Pemesan *</label>
                    <input type="text" name="passenger_name" value="{{ old('passenger_name') }}" required
                        placeholder="Masukkan nama lengkap sesuai KTP"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-sky-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs text-slate-300 mb-1.5">NIK / Nomor Identitas (16 Digit) *</label>
                    <input type="text" name="passenger_nik" value="{{ old('passenger_nik') }}" required
                        placeholder="Contoh: 6371012345670001"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-sky-400 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-slate-300 mb-1.5">Email Aktif *</label>
                        <input type="email" name="passenger_email" value="{{ old('passenger_email') }}" required
                            placeholder="nama@email.com"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-sky-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-300 mb-1.5">Nomor HP / WhatsApp *</label>
                        <input type="text" name="passenger_phone" value="{{ old('passenger_phone') }}" required
                            placeholder="0812xxxxxxxx"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-sm text-white focus:border-sky-400 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Real-time -->
        <div class="bg-slate-900 border border-sky-500/40 rounded-2xl p-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-sky-400 mb-4">Ringkasan Pilihan</h3>
            <div class="space-y-2.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-400">Penerbangan</span>
                    <span class="font-semibold text-white">{{ $flight->airline }} ({{ $flight->flight_code }})</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Rute</span>
                    <span class="text-white">{{ $flight->origin }} → {{ $flight->destination }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Kursi Terpilih</span>
                    <span id="display_seats" class="font-bold text-sky-400">-</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-slate-800 text-base">
                    <span class="font-bold text-white">Total Pembayaran</span>
                    <span id="display_total" class="font-extrabold text-sky-400">
                        Rp {{ number_format($flight->price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <button type="submit"
                class="mt-6 w-full py-3.5 px-6 rounded-xl bg-sky-500 hover:bg-sky-400 text-slate-950 font-extrabold text-sm transition shadow-lg shadow-sky-500/20">
                Konfirmasi & Terbitkan E-Ticket →
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ticketPrice = {{ $flight->price }};
        const qtySelect = document.getElementById('ticket_qty');
        const seatButtons = document.querySelectorAll('.seat-btn:not([disabled])');
        const hiddenInput = document.getElementById('selected_seats');
        const displaySeats = document.getElementById('display_seats');
        const displayTotal = document.getElementById('display_total');

        let selectedSeats = hiddenInput.value
            ? hiddenInput.value.split(',').map(s => s.trim()).filter(Boolean)
            : [];

        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }

        function updateUI() {
            const maxSeats = parseInt(qtySelect.value);

            // Jika kuota dikurangi, potong kelebihan kursi yang dipilih
            if (selectedSeats.length > maxSeats) {
                selectedSeats = selectedSeats.slice(0, maxSeats);
            }

            seatButtons.forEach(btn => {
                const seat = btn.dataset.seat;
                if (selectedSeats.includes(seat)) {
                    btn.classList.remove('bg-slate-800', 'text-slate-200', 'border-slate-600');
                    btn.classList.add('bg-sky-500', 'text-slate-950', 'border-sky-400');
                } else {
                    btn.classList.remove('bg-sky-500', 'text-slate-950', 'border-sky-400');
                    btn.classList.add('bg-slate-800', 'text-slate-200', 'border-slate-600');
                }
            });

            hiddenInput.value = selectedSeats.join(', ');
            displaySeats.textContent = selectedSeats.length > 0
                ? `${selectedSeats.join(', ')} (${selectedSeats.length}/${maxSeats} kursi)`
                : `Belum dipilih (0/${maxSeats} kursi)`;
            displayTotal.textContent = formatRupiah(maxSeats * ticketPrice);
        }

        seatButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const seat = this.dataset.seat;
                const maxSeats = parseInt(qtySelect.value);

                if (selectedSeats.includes(seat)) {
                    selectedSeats = selectedSeats.filter(s => s !== seat);
                } else {
                    if (selectedSeats.length >= maxSeats) {
                        alert(`Anda memilih kuota ${maxSeats} tiket. Hapus salah satu kursi terlebih dahulu atau tambah kuota tiket.`);
                        return;
                    }
                    selectedSeats.push(seat);
                }
                updateUI();
            });
        });

        qtySelect.addEventListener('change', updateUI);
        updateUI();
    });
</script>
@endpush
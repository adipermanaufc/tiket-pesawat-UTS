<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    // Halaman 1: Katalog Tiket & Pencarian Rute
    public function index(Request $request)
    {
        $query = Flight::query()->orderBy('flight_date', 'asc')->orderBy('departure_time', 'asc');

        if ($request->filled('origin')) {
            $query->where('origin', 'like', '%' . $request->origin . '%');
        }

        if ($request->filled('destination')) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }

        if ($request->filled('seat_class')) {
            $query->where('seat_class', $request->seat_class);
        }

        $flights = $query->get();

        return view('flights.index', compact('flights'));
    }

    // Halaman 2: Rincian Tiket
    public function show(Flight $flight)
    {
        return view('flights.show', compact('flight'));
    }

    // Halaman 3: Pemilihan Kuota / Kursi & Form Data Penumpang
    public function booking(Flight $flight)
    {
        if ($flight->available_seats <= 0) {
            return redirect()->route('flights.show', $flight)
                ->with('error', 'Maaf, kuota kursi untuk penerbangan ini sudah habis.');
        }

        $bookedSeats = $flight->getBookedSeats();

        return view('flights.booking', compact('flight', 'bookedSeats'));
    }

    // Proses Simpan Pemesanan & Update Kuota Kursi
    public function storeBooking(Request $request, Flight $flight)
    {
        $maxBookable = min(5, $flight->available_seats);

        $validated = $request->validate([
            'passenger_name'  => 'required|string|max:100',
            'passenger_nik'   => 'required|digits_between:12,16',
            'passenger_email' => 'required|email|max:100',
            'passenger_phone' => 'required|string|min:10|max:15',
            'ticket_qty'      => "required|integer|min:1|max:{$maxBookable}",
            'selected_seats'  => 'required|string',
        ], [
            'passenger_name.required'  => 'Nama lengkap pemesan wajib diisi.',
            'passenger_nik.required'   => 'NIK / Nomor identitas wajib diisi.',
            'passenger_nik.digits_between' => 'NIK harus berupa 12 hingga 16 digit angka.',
            'passenger_email.required' => 'Alamat email wajib diisi.',
            'passenger_phone.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'selected_seats.required'  => 'Silakan pilih kursi pada denah kabin.',
        ]);

        $chosenSeats = array_filter(array_map('trim', explode(',', $validated['selected_seats'])));

        if (count($chosenSeats) !== (int) $validated['ticket_qty']) {
            return back()->withInput()
                ->withErrors(['selected_seats' => "Jumlah kursi terpilih harus sesuai dengan kuota tiket ({$validated['ticket_qty']} kursi)."]);
        }

        // Cek bentrok kursi yang sudah dipesan
        $alreadyBooked = $flight->getBookedSeats();
        if (!empty(array_intersect($chosenSeats, $alreadyBooked))) {
            return back()->withInput()
                ->withErrors(['selected_seats' => 'Salah satu kursi baru saja dipesan orang lain. Silakan pilih kursi lain.']);
        }

        $booking = DB::transaction(function () use ($validated, $flight, $chosenSeats) {
            $lockedFlight = Flight::where('id', $flight->id)->lockForUpdate()->first();

            $bookingCode = Booking::generateBookingCode();
            $totalPrice  = $lockedFlight->price * (int) $validated['ticket_qty'];

            $newBooking = Booking::create([
                'booking_code'    => $bookingCode,
                'flight_id'       => $lockedFlight->id,
                'passenger_name'  => $validated['passenger_name'],
                'passenger_nik'   => $validated['passenger_nik'],
                'passenger_email' => $validated['passenger_email'],
                'passenger_phone' => $validated['passenger_phone'],
                'ticket_qty'      => (int) $validated['ticket_qty'],
                'selected_seats'  => implode(', ', $chosenSeats),
                'total_price'     => $totalPrice,
                'status'          => 'CONFIRMED',
            ]);

            $lockedFlight->decrement('available_seats', (int) $validated['ticket_qty']);

            return $newBooking;
        });

        return redirect()->route('flights.eticket', $booking->booking_code)
            ->with('success', 'Pemesanan tiket berhasil dikonfirmasi!');
    }

    // Halaman 4: Ringkasan & Bukti Pemesanan (E-Ticket)
    public function eticket(string $booking_code)
    {
        $booking = Booking::with('flight')->where('booking_code', $booking_code)->firstOrFail();

        return view('flights.eticket', compact('booking'));
    }
}
<?php

use App\Http\Controllers\FlightController;
use Illuminate\Support\Facades\Route;

// Halaman 1: Utama / Katalog Tiket (Home / Dashboard Screen)
Route::get('/', [FlightController::class, 'index'])->name('flights.index');

// Halaman 2: Rincian Tiket (Ticket Detail Screen)
Route::get('/flight/{flight}', [FlightController::class, 'show'])->name('flights.show');

// Halaman 3: Pemilihan Kuota / Kursi & Pengisian Data (Booking & Form Screen)
Route::get('/flight/{flight}/booking', [FlightController::class, 'booking'])->name('flights.booking');
Route::post('/flight/{flight}/booking', [FlightController::class, 'storeBooking'])->name('flights.store');

// Halaman 4: Ringkasan & Bukti Pemesanan (Summary & E-Ticket Screen)
Route::get('/e-ticket/{booking_code}', [FlightController::class, 'eticket'])->name('flights.eticket');
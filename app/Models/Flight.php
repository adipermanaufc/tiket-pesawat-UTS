<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_code',
        'airline',
        'origin',
        'destination',
        'flight_date',
        'departure_time',
        'arrival_time',
        'seat_class',
        'price',
        'total_seats',
        'available_seats',
        'baggage_info',
        'aircraft_type',
    ];

    protected $casts = [
        'flight_date' => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Mengambil daftar kursi yang sudah dipesan pada penerbangan ini
    public function getBookedSeats(): array
    {
        $seats = [];
        foreach ($this->bookings as $booking) {
            $booked = array_map('trim', explode(',', $booking->selected_seats));
            $seats = array_merge($seats, $booked);
        }
        return array_unique($seats);
    }
}
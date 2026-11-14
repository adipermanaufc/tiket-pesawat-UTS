<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'flight_id',
        'passenger_name',
        'passenger_nik',
        'passenger_email',
        'passenger_phone',
        'ticket_qty',
        'selected_seats',
        'total_price',
        'status',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public static function generateBookingCode(): string
    {
        do {
            $code = 'FLT-' . strtoupper(Str::random(6));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }
}
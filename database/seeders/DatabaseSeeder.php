<?php

namespace Database\Seeders;

use App\Models\Flight;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $flights = [
            [
                'flight_code' => 'GA-533',
                'airline' => 'Garuda Indonesia',
                'origin' => 'Banjarmasin (BDJ)',
                'destination' => 'Jakarta (CGK)',
                'flight_date' => Carbon::now()->addDays(2)->toDateString(),
                'departure_time' => '08:15:00',
                'arrival_time' => '09:05:00',
                'seat_class' => 'Ekonomi',
                'price' => 1350000,
                'total_seats' => 30,
                'available_seats' => 30,
                'baggage_info' => '20 Kg Bagasi + 7 Kg Kabin + Snack',
                'aircraft_type' => 'Boeing 737-800NG',
            ],
            [
                'flight_code' => 'JT-615',
                'airline' => 'Lion Air',
                'origin' => 'Banjarmasin (BDJ)',
                'destination' => 'Surabaya (SUB)',
                'flight_date' => Carbon::now()->addDays(2)->toDateString(),
                'departure_time' => '10:30:00',
                'arrival_time' => '10:45:00',
                'seat_class' => 'Ekonomi',
                'price' => 890000,
                'total_seats' => 30,
                'available_seats' => 30,
                'baggage_info' => '20 Kg Bagasi + 7 Kg Kabin',
                'aircraft_type' => 'Boeing 737-900ER',
            ],
            [
                'flight_code' => 'QG-481',
                'airline' => 'Citilink',
                'origin' => 'Banjarmasin (BDJ)',
                'destination' => 'Denpasar Bali (DPS)',
                'flight_date' => Carbon::now()->addDays(3)->toDateString(),
                'departure_time' => '13:20:00',
                'arrival_time' => '14:50:00',
                'seat_class' => 'Ekonomi',
                'price' => 1180000,
                'total_seats' => 30,
                'available_seats' => 30,
                'baggage_info' => '20 Kg Bagasi + 7 Kg Kabin',
                'aircraft_type' => 'Airbus A320neo',
            ],
            [
                'flight_code' => 'ID-6281',
                'airline' => 'Batik Air',
                'origin' => 'Jakarta (CGK)',
                'destination' => 'Banjarmasin (BDJ)',
                'flight_date' => Carbon::now()->addDays(4)->toDateString(),
                'departure_time' => '15:40:00',
                'arrival_time' => '18:30:00',
                'seat_class' => 'Bisnis',
                'price' => 2450000,
                'total_seats' => 30,
                'available_seats' => 30,
                'baggage_info' => '30 Kg Bagasi + Lounge + Makan Berat',
                'aircraft_type' => 'Airbus A320-200',
            ],
            [
                'flight_code' => 'IU-742',
                'airline' => 'Super Air Jet',
                'origin' => 'Banjarmasin (BDJ)',
                'destination' => 'Yogyakarta (YIA)',
                'flight_date' => Carbon::now()->addDays(5)->toDateString(),
                'departure_time' => '07:00:00',
                'arrival_time' => '07:35:00',
                'seat_class' => 'Ekonomi',
                'price' => 975000,
                'total_seats' => 30,
                'available_seats' => 30,
                'baggage_info' => '20 Kg Bagasi + 7 Kg Kabin',
                'aircraft_type' => 'Airbus A320-200',
            ],
            [
                'flight_code' => 'GA-539',
                'airline' => 'Garuda Indonesia',
                'origin' => 'Jakarta (CGK)',
                'destination' => 'Makassar (UPG)',
                'flight_date' => Carbon::now()->addDays(5)->toDateString(),
                'departure_time' => '19:10:00',
                'arrival_time' => '22:35:00',
                'seat_class' => 'Bisnis',
                'price' => 2890000,
                'total_seats' => 30,
                'available_seats' => 30,
                'baggage_info' => '30 Kg Bagasi + Priority Boarding',
                'aircraft_type' => 'Boeing 737-800NG',
            ],
        ];

        foreach ($flights as $flight) {
            Flight::create($flight);
        }
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_code')->unique();
            $table->string('airline');
            $table->string('origin');
            $table->string('destination');
            $table->date('flight_date');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->string('seat_class');
            $table->integer('price');
            $table->integer('total_seats')->default(30);
            $table->integer('available_seats')->default(30);
            $table->string('baggage_info')->default('20 Kg Bagasi + 7 Kg Kabin');
            $table->string('aircraft_type')->default('Boeing 737-800NG');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
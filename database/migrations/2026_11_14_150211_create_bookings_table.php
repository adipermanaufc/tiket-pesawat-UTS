<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('flight_id')->constrained('flights')->onDelete('cascade');
            $table->string('passenger_name');
            $table->string('passenger_nik', 20);
            $table->string('passenger_email');
            $table->string('passenger_phone', 20);
            $table->integer('ticket_qty');
            $table->string('selected_seats');
            $table->integer('total_price');
            $table->string('status')->default('CONFIRMED');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
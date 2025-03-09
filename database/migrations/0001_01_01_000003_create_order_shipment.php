<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_shipment', function (Blueprint $table) {
            $table->id();
            $table->string('idOrderBitship');
            $table->string('idTrackingBitship');
            $table->string('namaPengirim');
            $table->string('alamatPengirim');
            $table->string('nomorTelponPengirim');
            $table->string('namaPenerima');
            $table->string('alamatPenerima');
            $table->string('nomorTelponPenerima');
            $table->string('descBarang');
            $table->string('beratBarang');
            $table->string('hargaBarang');
            $table->string('createdBy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipment');
    }
};

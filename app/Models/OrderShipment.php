<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    use HasFactory;

    protected $table = 'order_shipment';

    protected $fillable = [
        'idOrderBitship',
        'idTrackingBitship',
        'namaPengirim',
        'alamatPengirim',
        'nomorTelponPengirim',
        'namaPenerima',
        'alamatPenerima',
        'nomorTelponPenerima',
        'descBarang',
        'beratBarang',
        'hargaBarang',
        'createdBy',
    ];
}
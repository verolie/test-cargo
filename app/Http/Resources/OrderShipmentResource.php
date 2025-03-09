<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'idOrderBitship' => $this->idOrderBitship,
            'idTrackingBitship' => $this->idTrackingBitship,
            'namaPengirimin' => $this->namaPengirimin,
            'alamatPengirim' => $this->alamatPengirim,
            'nomorTelponPengirim' => $this->nomorTelponPengirim,
            'namaPenerima' => $this->namaPenerima,
            'alamatPenerima' => $this->alamatPenerima,
            'nomorTelponPenerima' => $this->nomorTelponPenerima,
            'descBarang' => $this->descBarang,
            'beratBarang' => $this->beratBarang,
            'hargaBarang' => $this->hargaBarang,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

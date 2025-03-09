<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderShipmentResource;
use App\Models\OrderShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;

class OrderShipmentController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            // Ambil parameter query 'sort_by' dan 'order'
            $sortBy = $request->query('sort_by', 'id'); // default: id
            $order = strtolower($request->query('order', 'asc')) === 'desc' ? 'desc' : 'asc';

            // Daftar field yang diizinkan untuk sorting
            $allowedFields = [
                'id',
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
                'created_at',
                'updated_at'
            ];

            // Validasi field sorting
            if (!in_array($sortBy, $allowedFields)) {
                return $this->errorResponse("Invalid sort field", 422);
            }

            // Ambil data shipment
            $shipments = \App\Models\OrderShipment::orderBy($sortBy, $order)->get();

            // Hitung total data
            $total = $shipments->count();

            return $this->successResponse(
                'Order shipment details retrieved',
                200,
                [
                    'total' => $total,
                    'data' => \App\Http\Resources\OrderShipmentResource::collection($shipments)
                ]
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Endpoint create: POST /api/order-shipment
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'namaPengirim'         => 'required|string|max:255',
                'alamatPengirim'       => 'required|string',
                'nomorTelponPengirim'  => 'required|string',
                'namaPenerima'         => 'required|string|max:255',
                'alamatPenerima'       => 'required|string',
                'nomorTelponPenerima'  => 'required|string',
                'descBarang'           => 'required|string',
                'beratBarang'          => 'required|integer',
                'hargaBarang'          => 'required|integer',
                'createdBy'            => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            $biteshipData = [
                "shipper_contact_name" => $request->namaPengirim,
                "shipper_contact_phone" => $request->nomorTelponPengirim,
                "origin_contact_name" => $request->namaPengirim,
                "origin_contact_phone" => $request->nomorTelponPengirim,
                "origin_address" => $request->alamatPengirim,
                "origin_postal_code" => 12440,
                "destination_contact_name" => $request->namaPenerima,
                "destination_contact_phone" => $request->nomorTelponPenerima,
                "destination_address" => $request->alamatPenerima,
                "destination_postal_code" => 12950,
                "courier_company" => "jne",
                "courier_type" => "reg",
                "delivery_type" => "now",
                "order_note" => "Please be careful",
                "items" => [
                    [
                        "name" => $request->descBarang,
                        "description" => "Product Description",
                        "category" => "fashion",
                        "value" => $request->hargaBarang,
                        "quantity" => 1,
                        "weight" => $request->beratBarang
                    ]
                ]
            ];

            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://api.biteship.com/v1/orders', [
                'headers' => [
                    'authorization' => 'biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoidGVzdC1zaGlwIiwidXNlcklkIjoiNjdjZDBlYmZkMzhlODkwMDEyMWQ2ZmIzIiwiaWF0IjoxNzQxNDkyODYzfQ.tE1wAiJglo0NE2K3ubtAcaqE7A1VrsL4YkRHv7dksqY',
                    'Content-Type' => 'application/json'
                ],
                'json' => $biteshipData
            ]);

            $biteshipResponse = json_decode($response->getBody(), true);

            if (!isset($biteshipResponse['id']) || !isset($biteshipResponse['courier']['tracking_id'])) {
                return $this->errorResponse("Failed to create shipment in Biteship", 500);
            }

            $requestData = $request->all();
            $requestData['idOrderBitship'] = $biteshipResponse['id'];
            $requestData['idTrackingBitship'] = $biteshipResponse['courier']['tracking_id'];

            $shipment = \App\Models\OrderShipment::create($requestData);

            return $this->successResponse(
                'Order shipment created successfully',
                201,
                new \App\Http\Resources\OrderShipmentResource($shipment)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    // Endpoint update: PUT /api/order-shipment/{id}
    public function update(Request $request, $id)
    {
        try {
            $shipment = OrderShipment::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'idOrderBitship'       => 'sometimes|required|string',
                'idTrackingBitship'    => 'sometimes|required|string',
                'namaPengirim'         => 'sometimes|required|string|max:255',
                'alamatPengirim'       => 'sometimes|required|string',
                'nomorTelponPengirim'  => 'sometimes|required|string',
                'namaPenerima'         => 'sometimes|required|string|max:255',
                'alamatPenerima'       => 'sometimes|required|string',
                'nomorTelponPenerima'  => 'sometimes|required|string',
                'descBarang'           => 'sometimes|required|string',
                'beratBarang'          => 'sometimes|required|string',
                'hargaBarang'          => 'sometimes|required|string',
                'createdBy'            => 'sometimes|required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }

            // Ambil semua data kecuali 'id'
            $data = $request->except('id');

            $shipment->update($data);

            return $this->successResponse(
                'Order shipment updated successfully',
                200,
                new OrderShipmentResource($shipment)
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }


    // Endpoint delete: DELETE /api/order-shipment/{id}
    public function destroy($id)
    {
        try {
            $shipment = OrderShipment::findOrFail($id);

            // Hapus order di Biteship terlebih dahulu
            if ($shipment->idOrderBitship) {
                $client = new \GuzzleHttp\Client();
                $response = $client->post("https://api.biteship.com/v1/orders/{$shipment->idOrderBitship}/cancel", [
                    'headers' => [
                        'Authorization' => 'biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoidGVzdC1zaGlwIiwidXNlcklkIjoiNjdjZDBlYmZkMzhlODkwMDEyMWQ2ZmIzIiwiaWF0IjoxNzQxNDkyODYzfQ.tE1wAiJglo0NE2K3ubtAcaqE7A1VrsL4YkRHv7dksqY',
                        'Content-Type' => 'application/json'
                    ]
                ]);

                $biteshipResponse = json_decode($response->getBody(), true);
                if (!isset($biteshipResponse['success']) || !$biteshipResponse['success']) {
                    return $this->errorResponse("Failed to cancel order in Biteship", 500);
                }
            }

            $shipment->delete();

            return $this->successResponse(
                'Order shipment deleted successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}

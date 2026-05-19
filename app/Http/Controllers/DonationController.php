<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DonationController extends Controller
{
    #[OA\Get(
        path: "/api/donations",
        summary: "Lihat riwayat transaksi donasi",
        tags: ["Donations"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data donasi")
        ]
    )]
    public function index()
    {
        return response()->json(['message' => 'Riwayat donasi berhasil diambil'], 200);
    }

    #[OA\Post(
        path: "/api/donations",
        summary: "Buat transaksi donasi baru",
        tags: ["Donations"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "campaign_id", type: "integer", example: 1),
                    new OA\Property(property: "amount", type: "integer", example: 50000),
                    new OA\Property(property: "payment_method", type: "string", example: "QRIS")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Donasi Berhasil Diproses")
        ]
    )]
    public function store(Request $request)
    {
        return response()->json(['message' => 'Donasi berhasil diproses'], 201);
    }
}
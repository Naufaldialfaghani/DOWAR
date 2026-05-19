<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DistributionController extends Controller
{
    #[OA\Get(
        path: "/api/distributions",
        summary: "Lihat riwayat penyaluran barang",
        tags: ["Distributions"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil riwayat distribusi")
        ]
    )]
    public function index()
    {
        return response()->json(['message' => 'Riwayat distribusi berhasil diambil'], 200);
    }

    #[OA\Post(
        path: "/api/distributions",
        summary: "Catat pengiriman barang donasi baru",
        tags: ["Distributions"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "campaign_id", type: "integer", example: 1),
                    new OA\Property(property: "beneficiary_id", type: "integer", example: 1),
                    new OA\Property(property: "status", type: "string", example: "Terkirim"),
                    new OA\Property(property: "delivery_date", type: "string", format: "date", example: "2026-05-20")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Distribusi Berhasil Dicatat")
        ]
    )]
    public function store(Request $request)
    {
        return response()->json(['message' => 'Distribusi berhasil dicatat'], 201);
    }
}
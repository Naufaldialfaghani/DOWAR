<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    #[OA\Get(
        path: "/api/categories",
        summary: "Lihat semua kategori donasi",
        tags: ["Categories"],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data kategori")
        ]
    )]
    public function index()
    {
        // Sesuaikan dengan model Category milik kelompokmu
        return response()->json(['message' => 'List kategori berhasil diambil'], 200);
    }

    #[OA\Post(
        path: "/api/categories",
        summary: "Buat kategori baru",
        tags: ["Categories"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Bencana Alam"),
                    new OA\Property(property: "description", type: "string", example: "Kategori untuk korban bencana.")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Kategori Dibuat")
        ]
    )]
    public function store(Request $request)
    {
        return response()->json(['message' => 'Kategori berhasil dibuat'], 201);
    }
}
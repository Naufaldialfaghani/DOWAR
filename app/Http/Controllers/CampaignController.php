<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Campaigns")]

class CampaignController extends Controller
{
    // GET /api/campaigns - Menampilkan semua campaign (Read)
    #[OA\Get(
        path: "/api/campaigns",
        tags: ["Campaigns"],
        summary: "Ambil semua daftar campaign",
        responses: [
            new OA\Response(
                response: 200,
                description: "Daftar campaign berhasil diambil"
            )
        ]
    )]
    public function index()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'Daftar Program Donasi', 'data' => $campaigns], 200);
    }

    // POST /api/campaigns - Membuat campaign baru (Create)
    #[OA\Post(
        path: "/api/campaigns",
        tags: ["Campaigns"],
        summary: "Buat campaign baru",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title", "description", "target_date"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Bantuan Bencana"),
                    new OA\Property(property: "description", type: "string", example: "Penggalangan dana korban banjir"),
                    new OA\Property(property: "target_date", type: "string", format: "date", example: "2026-12-31"),
                    new OA\Property(property: "status", type: "string", example: "active")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Program donasi berhasil dibuat"
            ),
            new OA\Response(
                response: 422,
                description: "Validasi gagal"
            )
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_date' => 'required|date',
            'status' => 'in:active,completed'
        ]);

        $campaign = Campaign::create([
            'title' => $request->title,
            'description' => $request->description,
            'target_date' => $request->target_date,
            'status' => $request->status ?? 'active'
        ]);

        return response()->json(['message' => 'Program donasi berhasil dibuat', 'data' => $campaign], 201);
    }
}
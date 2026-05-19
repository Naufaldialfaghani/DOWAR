<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use OpenApi\Attributes as OA;

class CampaignController extends Controller
{
    #[OA\Get(
        path: "/api/campaigns",
        summary: "Lihat semua program donasi (Publik)",
        tags: ["Campaigns"],
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengambil data")
        ]
    )]
    public function index()
    {
        $campaigns = Campaign::all();
        return response()->json(['data' => $campaigns], 200);
    }

    #[OA\Post(
        path: "/api/campaigns",
        summary: "Buat program donasi baru (Hanya Admin)",
        tags: ["Campaigns"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Bantuan Pakaian Malang"),
                    new OA\Property(property: "description", type: "string", example: "Donasi pakaian layak pakai."),
                    new OA\Property(property: "target_date", type: "string", format: "date", example: "2026-07-25"),
                    new OA\Property(property: "status", type: "string", example: "active")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Campaign Dibuat"),
            new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'target_date' => 'required|date',
        ]);

        $campaign = Campaign::create([
            'title' => $request->title,
            'description' => $request->description,
            'target_date' => $request->target_date,
            'status' => $request->status ?? 'active',
        ]);

        return response()->json(['message' => 'Program donasi berhasil dibuat', 'data' => $campaign], 201);
    }

    #[OA\Get(
        path: "/api/campaigns/{id}",
        summary: "Detail program donasi spesifik",
        tags: ["Campaigns"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Data ditemukan"),
            new OA\Response(response: 404, description: "Data tidak ditemukan")
        ]
    )]
    public function show($id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['message' => 'Program donasi tidak ditemukan'], 404);
        }
        return response()->json(['data' => $campaign], 200);
    }

    #[OA\Put(
        path: "/api/campaigns/{id}",
        summary: "Update program donasi (Hanya Admin)",
        tags: ["Campaigns"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Bantuan Pakaian Malang V2"),
                    new OA\Property(property: "description", type: "string", example: "Update deskripsi."),
                    new OA\Property(property: "target_date", type: "string", format: "date", example: "2026-08-01"),
                    new OA\Property(property: "status", type: "string", example: "active")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Berhasil diupdate"),
            new OA\Response(response: 404, description: "Data tidak ditemukan")
        ]
    )]
    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['message' => 'Program donasi tidak ditemukan'], 404);
        }

        $campaign->update($request->all());
        return response()->json(['message' => 'Program donasi berhasil diupdate', 'data' => $campaign], 200);
    }

    #[OA\Delete(
        path: "/api/campaigns/{id}",
        summary: "Hapus program donasi (Hanya Admin)",
        tags: ["Campaigns"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil dihapus"),
            new OA\Response(response: 404, description: "Data tidak ditemukan")
        ]
    )]
    public function destroy($id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return response()->json(['message' => 'Program donasi tidak ditemukan'], 404);
        }

        $campaign->delete();
        return response()->json(['message' => 'Program donasi berhasil dihapus'], 200);
    }
}
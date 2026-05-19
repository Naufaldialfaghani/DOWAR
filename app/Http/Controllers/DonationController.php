<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Donations")]

class DonationController extends Controller
{
    #[OA\Get(
        path: "/api/donations",
        tags: ["Donations"],
        summary: "Ambil riwayat semua donasi",
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil mengambil data donasi"
            )
        ]
    )]
    public function index()
    {
        $donations = Donation::with(['user:id,name,email', 'campaign:id,title', 'category:id,category_name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil riwayat donasi.',
            'data'    => $donations
        ], 200);
    }

    #[OA\Post(
        path: "/api/donations",
        tags: ["Donations"],
        summary: "Membuat data donasi baru",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["campaign_id", "category_id", "item_name", "quantity"],
                properties: [
                    new OA\Property(property: "campaign_id", type: "integer", example: 1),
                    new OA\Property(property: "category_id", type: "integer", example: 2),
                    new OA\Property(property: "item_name", type: "string", example: "Beras 5kg"),
                    new OA\Property(property: "quantity", type: "integer", example: 3)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Donasi berhasil dicatat"
            ),
            new OA\Response(
                response: 422,
                description: "Validasi gagal"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized (butuh login)"
            )
        ]
    )]
    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'category_id' => 'required|exists:categories,id',
            'item_name'   => 'required|string|max:255',
            'quantity'    => 'required|integer|min:1',
        ]);

        $donation = Donation::create([
            'user_id'     => Auth::id(), 
            'campaign_id' => $request->campaign_id,
            'category_id' => $request->category_id,
            'item_name'   => $request->item_name,
            'quantity'    => $request->quantity,
            'status'      => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Donasi berhasil dicatat dan menunggu verifikasi.',
            'data'    => $donation->load(['campaign', 'category'])
        ], 201);
    }
}
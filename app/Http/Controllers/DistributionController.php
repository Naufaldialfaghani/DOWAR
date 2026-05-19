<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Distributions")]

class DistributionController extends Controller
{
    /**
     * GET /api/distributions
     */
    #[OA\Get(
        path: "/api/distributions",
        tags: ["Distributions"],
        summary: "Ambil semua data distribusi",
        responses: [
            new OA\Response(
                response: 200,
                description: "Riwayat distribusi berhasil diambil"
            ),
            new OA\Response(
                response: 500,
                description: "Terjadi kesalahan server"
            )
        ]
    )]
    public function index()
    {
        try {
        
            $distributions = Distribution::with(['donation', 'beneficiary'])->latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat distribusi barang berhasil diambil',
                'data' => $distributions
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data distribusi',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/distributions
     */
    #[OA\Post(
        path: "/api/distributions",
        tags: ["Distributions"],
        summary: "Mencatat log distribusi barang",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["donation_id", "beneficiary_id", "distribution_date"],
                properties: [
                    new OA\Property(property: "donation_id", type: "integer", example: 1),
                    new OA\Property(property: "beneficiary_id", type: "integer", example: 2),
                    new OA\Property(property: "distribution_date", type: "string", format: "date", example: "2026-05-19"),
                    new OA\Property(property: "note", type: "string", example: "Barang sudah diterima dengan baik")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Distribusi berhasil dicatat"
            ),
            new OA\Response(
                response: 422,
                description: "Validasi gagal"
            ),
            new OA\Response(
                response: 500,
                description: "Server error"
            )
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donation_id'       => 'required|exists:donations,id',
            'beneficiary_id'    => 'required|exists:beneficiaries,id',
            'distribution_date' => 'required|date',
            'note'              => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors'  => $validator->errors()
            ], 425);
        }

        try {
            $distribution = Distribution::create([
                'donation_id'       => $request->donation_id,
                'beneficiary_id'    => $request->beneficiary_id,
                'distribution_date' => $request->distribution_date,
                'note'              => $request->note,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log distribusi barang berhasil dicatat',
                'data'    => $distribution->load(['donation', 'beneficiary'])
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat log distribusi barang',
                'error'   => $th->getMessage()
            ], 500);
        }
    }
}
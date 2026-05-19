<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Beneficiaries")]

class BeneficiaryController extends Controller
{
    #[OA\Get(
        path: "/api/beneficiaries",
        tags: ["Beneficiaries"],
        summary: "Ambil semua data penerima manfaat",
        responses: [
            new OA\Response(
                response: 200,
                description: "Data berhasil diambil"
            )
        ]
    )]
    public function index()
    {
        $beneficiaries = Beneficiary::all();

        return response()->json([
            'message' => 'Data penerima manfaat berhasil diambil',
            'data'    => $beneficiaries
        ]);
    }

    #[OA\Post(
        path: "/api/beneficiaries",
        tags: ["Beneficiaries"],
        summary: "Tambah penerima manfaat baru",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "address", "phone"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Panti Asuhan Harapan"),
                    new OA\Property(property: "address", type: "string", example: "Jl. Mawar No. 10"),
                    new OA\Property(property: "phone", type: "string", example: "08123456789")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Berhasil ditambahkan"
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
            'name'    => 'required|string|max:255',
            'address' => 'required|string',
            'phone'   => 'required|string|max:20',
        ]);

        $beneficiary = Beneficiary::create([
            'name'    => $request->name,
            'address' => $request->address,
            'phone'   => $request->phone,
        ]);

        return response()->json([
            'message' => 'Penerima manfaat berhasil ditambahkan',
            'data'    => $beneficiary
        ], 201);
    }
}
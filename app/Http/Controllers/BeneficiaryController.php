<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class BeneficiaryController extends Controller
{
    #[OA\Get(
        path: "/api/beneficiaries",
        summary: "Tampilkan semua penerima manfaat",
        tags: ["Beneficiaries"],
        responses: [
            new OA\Response(response: 200, description: "Success")
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
        summary: "Tambah penerima manfaat baru",
        tags: ["Beneficiaries"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "address", "phone"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Panti Asuhan Harapan Bangsa"),
                    new OA\Property(property: "address", type: "string", example: "Jl. Merdeka No. 10, Malang"),
                    new OA\Property(property: "phone", type: "string", example: "08123456789")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Berhasil ditambahkan"),
            new OA\Response(response: 422, description: "Validasi gagal")
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

    #[OA\Put(
        path: "/api/beneficiaries/{id}",
        summary: "Update data penerima manfaat",
        tags: ["Beneficiaries"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Panti Asuhan Baru"),
                    new OA\Property(property: "address", type: "string", example: "Jl. Baru No. 5, Malang"),
                    new OA\Property(property: "phone", type: "string", example: "08987654321")
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
        $beneficiary = Beneficiary::find($id);

        if (!$beneficiary) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'name'    => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'phone'   => 'sometimes|string|max:20',
        ]);

        $beneficiary->update($request->only(['name', 'address', 'phone']));

        return response()->json([
            'message' => 'Data penerima manfaat berhasil diupdate',
            'data'    => $beneficiary
        ]);
    }

    #[OA\Delete(
        path: "/api/beneficiaries/{id}",
        summary: "Hapus penerima manfaat",
        tags: ["Beneficiaries"],
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
        $beneficiary = Beneficiary::find($id);

        if (!$beneficiary) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $beneficiary->delete();

        return response()->json([
            'message' => 'Data penerima manfaat berhasil dihapus'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    /**
     * GET /api/beneficiaries
     * Tampilkan semua penerima manfaat
     */ 
    public function index()
    {
        $beneficiaries = Beneficiary::all();

        return response()->json([
            'message' => 'Data penerima manfaat berhasil diambil',
            'data'    => $beneficiaries
        ]);
    }

    /**
     * POST /api/beneficiaries
     * Tambah penerima manfaat baru
     */
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
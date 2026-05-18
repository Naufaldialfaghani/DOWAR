<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistributionController extends Controller
{
    /**
     * GET /api/distributions
     * Menampilkan catatan transparansi riwayat penyaluran barang (Read)
     */
    public function index()
    {
        try {
           
            $distributions = Distribution::with('beneficiary')->latest()->get();

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
     * Membuat log pengiriman barang donasi kepada pihak beneficiaries (Create)
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'item_name'      => 'required|string|max:255',
            'quantity'       => 'required|integer|min:1',
            'unit'           => 'required|string|max:50',
            'distributed_at' => 'required|date',
            'notes'          => 'nullable|string'
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
                'beneficiary_id' => $request->beneficiary_id,
                'item_name'      => $request->item_name,
                'quantity'       => $request->quantity,
                'unit'           => $request->unit,
                'distributed_at' => $request->distributed_at,
                'notes'          => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Log distribusi barang berhasil dicatat',
                'data'    => $distribution->load('beneficiary') 
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
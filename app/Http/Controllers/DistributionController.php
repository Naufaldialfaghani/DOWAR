<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistributionController extends Controller
{
    /**
     * GET /api/distributions
     */
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
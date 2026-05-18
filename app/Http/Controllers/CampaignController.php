<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
    // GET /api/campaigns - Menampilkan semua campaign (Read)
    public function index()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'Daftar Program Donasi', 'data' => $campaigns], 200);
    }

    // POST /api/campaigns - Membuat campaign baru (Create)
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
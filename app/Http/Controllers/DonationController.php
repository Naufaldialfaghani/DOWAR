<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
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
            'status'      => 'pending' // Default status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Donasi berhasil dicatat dan menunggu verifikasi.',
            'data'    => $donation->load(['campaign', 'category'])
        ], 201);
    }
}

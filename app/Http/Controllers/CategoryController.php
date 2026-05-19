<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Categories")]

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // GET /api/categories - Menampilkan semua kategori (Read)
    #[OA\Get(
        path: "/api/categories",
        tags: ["Categories"],
        summary: "Ambil semua kategori",
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil mengambil data kategori"
            )
        ]
    )]
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // POST /api/categories - Menambah kategori baru (Create)
    #[OA\Post(
        path: "/api/categories",
        tags: ["Categories"],
        summary: "Tambah kategori baru",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["category_name"],
                properties: [
                    new OA\Property(property: "category_name", type: "string", example: "Pakaian")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category berhasil ditambahkan"
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
            'category_name' => 'required'
        ]);

        $category = Category::create([
            'category_name' => $request->category_name
        ]);

        return response()->json([
            'message' => 'Category berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
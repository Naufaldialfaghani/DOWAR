<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;


#[OA\Tag(name: "Auth")]
class AuthController extends Controller
{
    #[OA\Post(
        path: "/api/auth/register",
        summary: "Register akun baru",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Naufaldi Admin"),
                    new OA\Property(property: "email", type: "string", example: "naufaldi@dowar.com"),
                    new OA\Property(property: "password", type: "string", example: "password123"),
                    new OA\Property(property: "role", type: "string", example: "admin")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Register Sukses")
        ]
    )]
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'donatur'
        ]);

        return response()->json(['message' => 'Registrasi berhasil', 'user' => $user], 201);
    }

    #[OA\Post(
        path: "/api/auth/login",
        summary: "Login dan dapatkan Token JWT",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string", example: "naufaldi@dowar.com"),
                    new OA\Property(property: "password", type: "string", example: "password123")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Login Sukses"),
            new OA\Response(response: 401, description: "Kredensial Salah")
        ]
    )]
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        /** @var \Tymon\JWTAuth\JWTGuard $auth */
        $auth = auth('api');

        if (!$token = $auth->attempt($credentials)) {
            return response()->json(['error' => 'Email atau Password salah.'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $auth->factory()->getTTL() * 60,
            'user' => $auth->user()
        ]);
    }
}

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
        tags: ["Auth"],
        summary: "Register user baru",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Zanita"),
                    new OA\Property(property: "email", type: "string", example: "zanita@mail.com"),
                    new OA\Property(property: "password", type: "string", example: "123456"),
                    new OA\Property(property: "role", type: "string", example: "donatur")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Registrasi berhasil"
            ),
            new OA\Response(
                response: 422,
                description: "Validasi gagal"
            )
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
        tags: ["Auth"],
        summary: "Login user dan dapatkan JWT token",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", example: "user@mail.com"),
                    new OA\Property(property: "password", type: "string", example: "123456")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login berhasil, return token JWT"
            ),
            new OA\Response(
                response: 401,
                description: "Email atau password salah"
            )
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
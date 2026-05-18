<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FeedbackController extends Controller
{
    #[OA\Get(
        path: "/feedbacks",
        tags: ["Feedbacks"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success"
            )
        ]
    )]
    public function index()
    {
        return Feedback::all();
    }

    #[OA\Post(
        path: "/feedbacks",
        tags: ["Feedbacks"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "message"],
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "message", type: "string"),
                    new OA\Property(property: "rating", type: "integer")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Created"
            )
        ]
    )]
    public function store(Request $request)
    {
        return Feedback::create($request->all());
    }
}
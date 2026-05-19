<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Server(
    url: "http://localhost:8000",
    description: "Local Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
abstract class Controller {}
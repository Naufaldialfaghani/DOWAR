<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "DOWAR API",
    version: "1.0.0",
    description: "API Donasi & Feedback System"
)]
#[OA\Server(
    url: "http://localhost:8000/api",
    description: "Local Server"
)]
class OpenApi {}
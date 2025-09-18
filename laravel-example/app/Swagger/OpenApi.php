<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\info(
 *  title="Api en Laravel"
 *  version="1.0.0",
 *  description="Documentacion bien chida con Laravel 11 y Passport"
 * )
 * 
 * @OA\Server(
 *  url=L5_SWAGGER_CONST_HOST,
 *  description="Servel local"
 * )
 * 
 * @OA\SecurityScheme(
 *  securityScheme="bearerAuth",
 *  type="http",
 *  scheme="bearer",
 *  bearerFormat="JWT"
 * )
 * 
 * @OA\Tag(name="Auth", description="Autentificacion y Perfil")
 * @OA\Tag(name="Posts", description="Posts bien lindos para los Usuarios)
 */

class OpenApi {}
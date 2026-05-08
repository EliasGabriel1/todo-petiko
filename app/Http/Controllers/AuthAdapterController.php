<?php

/**
 * @OA\Info(
 *     title="Todo App API",
 *     version="1.0.0",
 *     description="Todo app API with auth adapter"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Adapters\AuthServiceAdapterInterface;
use Illuminate\Http\Request;

class AuthAdapterController extends Controller
{
    public function __construct(private AuthServiceAdapterInterface $authAdapter)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user through auth adapter",
     *     tags={"Auth Adapter"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully")
     * )
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $response = $this->authAdapter->register($data);

        return response()->json($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login through auth adapter",
     *     tags={"Auth Adapter"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@test.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $response = $this->authAdapter->login($data['email'], $data['password']);

        if (! $response) {
            return response()->json([
                'message' => 'Credenciais inválidas',
            ], 401);
        }

        return response()->json($response);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     summary="Get authenticated user through auth adapter",
     *     tags={"Auth Adapter"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Authenticated user returned"),
     *     @OA\Response(response=401, description="Token missing or invalid")
     * )
     */
    public function me(Request $request)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'message' => 'Token não fornecido',
            ], 401);
        }

        $user = $this->authAdapter->me($token);

        if (! $user) {
            return response()->json([
                'message' => 'Token inválido',
            ], 401);
        }

        return response()->json($user);
    }
}

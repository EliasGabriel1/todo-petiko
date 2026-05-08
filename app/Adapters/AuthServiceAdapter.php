<?php

namespace App\Adapters;

use Illuminate\Support\Facades\Http;

class AuthServiceAdapter implements AuthServiceAdapterInterface
{
    public function register(array $data): array
    {
        $response = $this->client()->post('/api/register', $data);

        return $response->json();
    }

    public function login(string $email, string $password): ?array
    {
        $response = $this->client()->post('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->status() === 401) {
            return null;
        }

        return $response->json();
    }

    public function me(string $token): ?array
    {
        $response = $this->client()
            ->withToken($token)
            ->get('/api/me');

        if ($response->status() === 401) {
            return null;
        }

        return $response->json();
    }

    private function client()
    {
        return Http::baseUrl(config('services.auth_service.base_url'))
            ->acceptJson();
    }
}

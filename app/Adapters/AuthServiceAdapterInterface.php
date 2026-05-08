<?php

namespace App\Adapters;

interface AuthServiceAdapterInterface
{
    public function register(array $data): array;

    public function login(string $email, string $password): ?array;

    public function me(string $token): ?array;
}

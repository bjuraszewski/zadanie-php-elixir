<?php

namespace App\Service;

use App\DTO\UserDataDTO;
use App\DTO\UserFilterDTO;
use App\Contract\PhoenixApiInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PhoenixApiService implements PhoenixApiInterface
{
    public function __construct(
        private HttpClientInterface $phoenixClient,
    ) {
    }

    public function getUsers(UserFilterDTO $filterDTO): array
    {
        try {
            $response = $this->phoenixClient->request('GET', '/users', [
                'query' => $filterDTO->toArray(),
            ]);
            return $response->toArray()['data'];
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function createUser(UserDataDTO $userData): array
    {
        try {
            $response = $this->phoenixClient->request('POST', '/users', [
                'json' => ['user' => $userData->toArray()],
            ]);
            return $response->toArray();
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function updateUser(int $id, UserDataDTO $userData): array
    {
        try {
            $response = $this->phoenixClient->request('PUT', "/users/$id", [
                'json' => ['user' => $userData->toArray()],
            ]);
            return $response->toArray();
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function deleteUser(int $id): bool
    {
        try {
            $response = $this->phoenixClient->request('DELETE', "/users/$id");
            return $response->getStatusCode() === 204;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

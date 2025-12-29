<?php

namespace App\Contract;

use App\DTO\UserDataDTO;
use App\DTO\UserFilterDTO;

interface PhoenixApiInterface
{
    public function getUsers(UserFilterDTO $filterDTO): array;

    public function createUser(UserDataDTO $userData): array;

    public function updateUser(int $id, UserDataDTO $userData): array;

    public function deleteUser(int $id): bool;
    public function importUsers(): array;
}

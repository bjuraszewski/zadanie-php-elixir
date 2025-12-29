<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserFilterDTO
{
    public ?string $first_name = null;

    public ?string $last_name = null;

    public ?string $gender = null;

    public ?string $born_after = null;

    public ?string $born_before = null;

    public ?string $sort_by = null;

    public ?string $sort_order = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = get_object_vars($this);

        return array_filter($data, fn($value) => $value !== null && $value !== '');
    }
}

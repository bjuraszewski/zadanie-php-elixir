<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDataDTO
{
    #[Assert\NotBlank]
    public string $first_name;

    #[Assert\NotBlank]
    public string $last_name;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['male', 'female'], message: 'Choose a valid gender.')]
    public string $gender;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $birthdate;

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
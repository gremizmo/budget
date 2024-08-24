<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

final readonly class EditUserDto implements EditUserDtoInterface
{
    public function __construct(
        public string $firstname,
        public string $lastname,
    ) {
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }
}

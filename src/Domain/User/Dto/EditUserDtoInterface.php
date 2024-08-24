<?php

declare(strict_types=1);

namespace App\Domain\User\Dto;

interface EditUserDtoInterface
{
    public function getFirstname(): string;

    public function getLastname(): string;
}

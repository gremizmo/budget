<?php

declare(strict_types=1);

namespace App\Application\User\Dto;

interface EditUserInputInterface
{
    public function getFirstname(): string;

    public function getLastname(): string;
}

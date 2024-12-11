<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Dto;

interface EditUserInputInterface
{
    public function getFirstname(): string;

    public function getLastname(): string;
}
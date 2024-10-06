<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Dto;

interface CreateUserInputInterface
{
    public function getEmail(): string;

    public function getPassword(): string;

    public function getFirstname(): string;

    public function getLastname(): string;

    public function isConsentGiven(): bool;
}

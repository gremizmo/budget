<?php

declare(strict_types=1);

namespace App\UserManagement\Application\Query;

use App\UserManagement\Domain\Query\QueryInterface;

readonly class GetUserAlreadyExistsQuery implements QueryInterface
{
    public function __construct(private string $userEmail)
    {
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }
}

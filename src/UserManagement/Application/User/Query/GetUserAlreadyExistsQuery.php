<?php

declare(strict_types=1);

namespace App\UserManagement\Application\User\Query;

use App\UserManagement\Domain\Shared\Query\QueryInterface;

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

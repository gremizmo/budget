<?php

namespace App\UserManagement\Application\Query;

use App\UserManagement\Domain\Query\QueryInterface;

readonly class GetUserByPasswordResetTokenQuery implements QueryInterface
{
    public function __construct(private string $userPasswordResetToken)
    {
    }

    public function getUserPasswordResetToken(): string
    {
        return $this->userPasswordResetToken;
    }
}

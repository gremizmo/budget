<?php

namespace App\UserManagement\Application\User\Query;

use App\UserManagement\Domain\User\Query\QueryInterface;

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

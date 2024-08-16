<?php

namespace App\Application\User\Query;

use App\Domain\Shared\Query\QueryInterface;

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

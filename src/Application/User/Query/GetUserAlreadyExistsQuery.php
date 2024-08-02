<?php

declare(strict_types=1);

namespace App\Application\User\Query;

use App\Domain\Shared\Query\QueryInterface;

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

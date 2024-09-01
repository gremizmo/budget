<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Query;

use App\EnvelopeManagement\Domain\Shared\Query\QueryInterface;

readonly class GetEnvelopeByTitleQuery implements QueryInterface
{
    public function __construct(private string $title, private int $userId)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}

<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Query;

use App\EnvelopeManagement\Domain\Envelope\Query\QueryInterface;

readonly class GetEnvelopeByTitleQuery implements QueryInterface
{
    public function __construct(private string $title, private string $userUuid)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}

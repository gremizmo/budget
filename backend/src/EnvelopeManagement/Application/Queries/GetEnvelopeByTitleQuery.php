<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Queries;

use App\EnvelopeManagement\Domain\Ports\Inbound\QueryInterface;

final readonly class GetEnvelopeByTitleQuery implements QueryInterface
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

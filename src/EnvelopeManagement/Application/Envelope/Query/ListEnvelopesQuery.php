<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Query;

use App\EnvelopeManagement\Application\Envelope\Dto\ListEnvelopesInputInterface;
use App\EnvelopeManagement\Domain\Shared\Query\QueryInterface;

readonly class ListEnvelopesQuery implements QueryInterface
{
    public function __construct(
        private int $userId,
        private ListEnvelopesInputInterface $listEnvelopesDto,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getListEnvelopesDto(): ListEnvelopesInputInterface
    {
        return $this->listEnvelopesDto;
    }
}

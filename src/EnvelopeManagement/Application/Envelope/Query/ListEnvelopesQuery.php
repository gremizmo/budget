<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Query;

use App\EnvelopeManagement\Application\Envelope\Dto\ListEnvelopesInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Query\QueryInterface;

readonly class ListEnvelopesQuery implements QueryInterface
{
    public function __construct(
        private string $userUuid,
        private ListEnvelopesInputInterface $listEnvelopesDto,
    ) {
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getListEnvelopesDto(): ListEnvelopesInputInterface
    {
        return $this->listEnvelopesDto;
    }
}

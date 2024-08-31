<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Application\Envelope\Query;

use App\EnvelopeManagement\Application\Envelope\Dto\ListEnvelopesInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\UserInterface;
use App\EnvelopeManagement\Domain\Shared\Query\QueryInterface;

readonly class ListEnvelopesQuery implements QueryInterface
{
    public function __construct(
        private UserInterface $user,
        private ListEnvelopesInputInterface $listEnvelopesDto,
    ) {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getListEnvelopesDto(): ListEnvelopesInputInterface
    {
        return $this->listEnvelopesDto;
    }
}

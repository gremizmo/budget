<?php

declare(strict_types=1);

namespace App\Application\Envelope\Query;

use App\Application\Envelope\Dto\ListEnvelopesInputInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Domain\Shared\Query\QueryInterface;

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

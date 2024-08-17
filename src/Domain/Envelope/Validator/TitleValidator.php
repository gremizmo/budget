<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\User\Entity\UserInterface;

class TitleValidator
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    /**
     * @throws EnvelopeTitleAlreadyExistsForUserException
     */
    public function validate(string $title, ?UserInterface $user = null, ?EnvelopeInterface $envelopeToUpdate = null): void
    {
        $envelopeToUpdateUser = $envelopeToUpdate?->getUser();

        if (!$user instanceof UserInterface && !$envelopeToUpdateUser instanceof UserInterface) {
            return;
        }

        $envelope = $this->queryBus->query(new GetEnvelopeByTitleQuery($title, $user ?? $envelopeToUpdateUser));

        if ($envelope instanceof EnvelopeInterface && $envelope->getId() !== $envelopeToUpdate?->getId()) {
            throw new EnvelopeTitleAlreadyExistsForUserException(EnvelopeTitleAlreadyExistsForUserException::MESSAGE, 400);
        }
    }
}

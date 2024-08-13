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
    {}

    /**
     * @throws EnvelopeTitleAlreadyExistsForUserException
     */
    public function validate(string $title, ?UserInterface $user = null, ?EnvelopeInterface $envelopeToUpdate = null): void
    {
        $envelope = $this->queryBus->query(new GetEnvelopeByTitleQuery($title, $user ?? $envelopeToUpdate?->getUser()));

        if ($envelope && $envelope->getId() !== $envelopeToUpdate?->getId()) {
            throw new EnvelopeTitleAlreadyExistsForUserException(EnvelopeTitleAlreadyExistsForUserException::MESSAGE, 400);
        }
    }
}

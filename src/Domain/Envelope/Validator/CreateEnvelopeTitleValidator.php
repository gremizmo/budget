<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\User\Entity\UserInterface;

readonly class CreateEnvelopeTitleValidator
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    /**
     * @throws EnvelopeTitleAlreadyExistsForUserException
     */
    public function validate(string $title, UserInterface $user): void
    {
        $envelope = $this->queryBus->query(new GetEnvelopeByTitleQuery($title, $user));

        if ($envelope instanceof EnvelopeInterface) {
            throw new EnvelopeTitleAlreadyExistsForUserException(EnvelopeTitleAlreadyExistsForUserException::MESSAGE, 400);
        }
    }
}

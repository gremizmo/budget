<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Validator;

use App\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;

readonly class EditEnvelopeTitleValidator
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    /**
     * @throws EnvelopeTitleAlreadyExistsForUserException
     */
    public function validate(string $title, EnvelopeInterface $envelopeToUpdate): void
    {
        $envelope = $this->queryBus->query(new GetEnvelopeByTitleQuery($title, $envelopeToUpdate->getUser()));

        if ($envelope instanceof EnvelopeInterface && $envelope->getId() !== $envelopeToUpdate->getId()) {
            throw new EnvelopeTitleAlreadyExistsForUserException(EnvelopeTitleAlreadyExistsForUserException::MESSAGE, 400);
        }
    }
}

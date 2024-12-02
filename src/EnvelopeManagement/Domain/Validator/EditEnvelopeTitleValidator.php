<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Validator;

use App\EnvelopeManagement\Application\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Exception\EnvelopeTitleAlreadyExistsForUserException;

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
        $envelope = $this->queryBus->query(new GetEnvelopeByTitleQuery($title, $envelopeToUpdate->getUserUuid()));

        if ($envelope instanceof EnvelopeInterface && $envelope->getUuid() !== $envelopeToUpdate->getUuid()) {
            throw new EnvelopeTitleAlreadyExistsForUserException(EnvelopeTitleAlreadyExistsForUserException::MESSAGE, 400);
        }
    }
}

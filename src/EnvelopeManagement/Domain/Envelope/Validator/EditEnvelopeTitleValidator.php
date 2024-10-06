<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Validator;

use App\EnvelopeManagement\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Domain\Envelope\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

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

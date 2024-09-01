<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Validator;

use App\EnvelopeManagement\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Shared\Adapter\QueryBusInterface;

readonly class CreateEnvelopeTitleValidator
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    /**
     * @throws EnvelopeTitleAlreadyExistsForUserException
     */
    public function validate(string $title, int $userId): void
    {
        $envelope = $this->queryBus->query(new GetEnvelopeByTitleQuery($title, $userId));

        if ($envelope instanceof EnvelopeInterface) {
            throw new EnvelopeTitleAlreadyExistsForUserException(EnvelopeTitleAlreadyExistsForUserException::MESSAGE, 400);
        }
    }
}

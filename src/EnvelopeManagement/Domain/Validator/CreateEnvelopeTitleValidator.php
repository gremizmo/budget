<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Validator;

use App\EnvelopeManagement\Application\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Aggregate\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Exception\EnvelopeTitleAlreadyExistsForUserException;

readonly class CreateEnvelopeTitleValidator
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    /**
     * @throws EnvelopeTitleAlreadyExistsForUserException
     */
    public function validate(string $title, string $userUuid): void
    {
        $envelope = $this->queryBus->query(new GetEnvelopeByTitleQuery($title, $userUuid));

        if ($envelope instanceof EnvelopeInterface) {
            throw new EnvelopeTitleAlreadyExistsForUserException(EnvelopeTitleAlreadyExistsForUserException::MESSAGE, 400);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Validator;

use App\EnvelopeManagement\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\EnvelopeManagement\Domain\Envelope\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;

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

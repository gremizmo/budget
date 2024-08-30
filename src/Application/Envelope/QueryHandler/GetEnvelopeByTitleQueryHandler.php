<?php

declare(strict_types=1);

namespace App\Application\Envelope\QueryHandler;

use App\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Envelope\Repository\EnvelopeQueryRepositoryInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class GetEnvelopeByTitleQueryHandler
{
    public function __construct(
        private EnvelopeQueryRepositoryInterface $envelopeQueryRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws GetEnvelopeByTitleQueryHandlerException
     */
    public function __invoke(GetEnvelopeByTitleQuery $getOneEnvelopeQuery): ?EnvelopeInterface
    {
        try {
            return $this->envelopeQueryRepository->findOneBy([
                'title' => $getOneEnvelopeQuery->getTitle(),
                'user' => $getOneEnvelopeQuery->getUser()->getId(),
            ]);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new GetEnvelopeByTitleQueryHandlerException(GetEnvelopeByTitleQueryHandlerException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}

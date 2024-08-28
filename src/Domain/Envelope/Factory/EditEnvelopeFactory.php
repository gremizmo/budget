<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\EditEnvelopeBuilderInterface;
use App\Domain\Envelope\Dto\EditEnvelopeDtoInterface;
use App\Domain\Envelope\Exception\Factory\EditEnvelopeFactoryException;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class EditEnvelopeFactory implements EditEnvelopeFactoryInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private EditEnvelopeBuilderInterface $editEnvelopeBuilder,
    ) {
    }

    /**
     * @throws EditEnvelopeFactoryException
     */
    public function createFromDto(
        EnvelopeInterface $envelope,
        EditEnvelopeDtoInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        try {
            return $this->editEnvelopeBuilder->setEnvelope($envelope)
                ->setUpdateEnvelopeDto($updateEnvelopeDto)
                ->setParentEnvelope($parentEnvelope)
                ->build();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code' => $exception->getCode(),
            ]);
            throw new EditEnvelopeFactoryException(EditEnvelopeFactoryException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}

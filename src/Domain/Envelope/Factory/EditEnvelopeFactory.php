<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\EditEnvelopeBuilderInterface;
use App\Domain\Envelope\Dto\EditEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\SelfParentEnvelopeException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class EditEnvelopeFactory implements EditEnvelopeFactoryInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private EditEnvelopeBuilderInterface $editEnvelopeBuilder,
    ) {
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function createFromDto(
        EnvelopeInterface $envelope,
        EditEnvelopeDtoInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        $this->editEnvelopeBuilder->setEnvelope($envelope)
            ->setUpdateEnvelopeDto($updateEnvelopeDto)
            ->setParentEnvelope($parentEnvelope);

        try {
            return $this->editEnvelopeBuilder->build();
        } catch (ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException|EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException|SelfParentEnvelopeException $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code'      => $exception->getCode(),
            ]);
            throw $exception;
        }
    }
}

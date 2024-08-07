<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\EditEnvelopeBuilderInterface;
use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeInvalidArgumentsException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
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
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeInvalidArgumentsException
     */
    public function createFromDto(
        EnvelopeInterface $envelope,
        UpdateEnvelopeDtoInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        $this->editEnvelopeBuilder->setEnvelope($envelope)
            ->setUpdateEnvelopeDto($updateEnvelopeDto)
            ->setParentEnvelope($parentEnvelope);

        try {
            return $this->editEnvelopeBuilder->build();
        } catch (ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException|ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException|EnvelopeInvalidArgumentsException $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code'      => $exception->getCode(),
            ]);
            throw $exception;
        }
    }
}

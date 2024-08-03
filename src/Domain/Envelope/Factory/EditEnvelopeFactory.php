<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\EditEnvelopeBuilder;
use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class EditEnvelopeFactory implements EditEnvelopeFactoryInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    public function createFromDto(
        EnvelopeInterface $envelope,
        UpdateEnvelopeDtoInterface $updateEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope = null,
    ): EnvelopeInterface {
        $builder = new EditEnvelopeBuilder();
        $builder->setEnvelope($envelope)
            ->setUpdateEnvelopeDto($updateEnvelopeDto)
            ->setParentEnvelope($parentEnvelope);

        try {
            return $builder->build();
        } catch (ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException|ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code'      => $exception->getCode(),
            ]);
            throw $exception;
        }
    }
}

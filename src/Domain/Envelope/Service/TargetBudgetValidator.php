<?php

namespace App\Domain\Envelope\Service;

use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsCurrentException;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentException;
use App\Domain\Shared\Adapter\LoggerInterface;

readonly class TargetBudgetValidator implements TargetBudgetValidatorInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentException|ChildrenTargetBudgetsExceedsCurrentException
     */
    public function validate(float $targetBudget, ?EnvelopeInterface $parentEnvelope = null, ?EnvelopeInterface $currentEnvelope = null): void
    {
        match (true) {
            null !== $parentEnvelope => $this->validateParentEnvelope($parentEnvelope, $targetBudget),
            null !== $currentEnvelope => $this->validateCurrentEnvelope($currentEnvelope, $targetBudget),
            default => null,
        };
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentException
     */
    private function validateParentEnvelope(EnvelopeInterface $parentEnvelope, float $targetBudget): void
    {
        if ($parentEnvelope->exceedsParentEnvelopeTargetBudget($targetBudget)) {
            $this->logger->error(
                ChildrenTargetBudgetsExceedsParentException::MESSAGE,
                [
                    'parentEnvelope' => $parentEnvelope->getId(),
                    'parentEnvelopeTargetBudget' => $parentEnvelope->getTargetBudget(),
                    'currentEnvelopeTargetBudget' => $targetBudget,
                ]
            );
            throw new ChildrenTargetBudgetsExceedsParentException(ChildrenTargetBudgetsExceedsParentException::MESSAGE, 400);
        }
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsCurrentException
     */
    private function validateCurrentEnvelope(EnvelopeInterface $currentEnvelope, float $targetBudget): void
    {
        if ($currentEnvelope->exceedsCurrentEnvelopeTargetBudget($targetBudget)) {
            $this->logger->error(
                ChildrenTargetBudgetsExceedsParentException::MESSAGE,
                [
                    'currentEnvelopeTargetBudget' => $targetBudget,
                ]
            );
            throw new ChildrenTargetBudgetsExceedsCurrentException(ChildrenTargetBudgetsExceedsCurrentException::MESSAGE, 400);
        }
    }
}

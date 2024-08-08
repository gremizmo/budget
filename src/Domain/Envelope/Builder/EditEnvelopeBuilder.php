<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\SelfParentEnvelopeException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Validator\TargetBudgetValidator;
use App\Domain\Envelope\Validator\CurrentBudgetValidator;

class EditEnvelopeBuilder implements EditEnvelopeBuilderInterface
{
    private ?EnvelopeInterface $envelope = null;
    private ?UpdateEnvelopeDtoInterface $updateEnvelopeDto = null;
    private ?EnvelopeInterface $parentEnvelope = null;
    private TargetBudgetValidator $targetBudgetValidator;
    private CurrentBudgetValidator $currentBudgetValidator;

    public function __construct(
        TargetBudgetValidator $targetBudgetValidator,
        CurrentBudgetValidator $currentBudgetValidator
    ) {
        $this->targetBudgetValidator = $targetBudgetValidator;
        $this->currentBudgetValidator = $currentBudgetValidator;
    }

    public function setEnvelope(EnvelopeInterface $envelope): self
    {
        $this->envelope = $envelope;

        return $this;
    }

    public function setUpdateEnvelopeDto(UpdateEnvelopeDtoInterface $updateEnvelopeDto): self
    {
        $this->updateEnvelopeDto = $updateEnvelopeDto;

        return $this;
    }

    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self
    {
        $this->parentEnvelope = $parentEnvelope;

        return $this;
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    public function build(): EnvelopeInterface
    {
        $this->validateInputs();

        $difference = $this->calculateDifference();
        $oldEnvelopeParentId = $this->envelope->getParent()?->getId();

        $this->handleParentChange($oldEnvelopeParentId);

        $this->updateEnvelopeProperties();

        $this->updateBudgets($difference, $oldEnvelopeParentId);

        return $this->envelope;
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     */
    private function validateInputs(): void
    {
        if ($this->parentEnvelope?->getId() === $this->envelope->getId()) {
            throw new SelfParentEnvelopeException('Envelope cannot be its own parent.', 400);
        }

        $this->targetBudgetValidator->validate($this->updateEnvelopeDto->getTargetBudget(), $this->parentEnvelope, $this->envelope);
        $this->currentBudgetValidator->validate($this->updateEnvelopeDto->getCurrentBudget(), $this->parentEnvelope);
    }

    private function calculateDifference(): float
    {
        return floatval($this->updateEnvelopeDto->getCurrentBudget()) - floatval($this->envelope->getCurrentBudget());
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function handleParentChange(?int $oldEnvelopeParentId): void
    {
        if ($this->envelope->getParent() && $oldEnvelopeParentId !== $this->parentEnvelope?->getId()) {
            $this->updateAncestorsCurrentBudget($this->envelope->getParent(), -floatval($this->envelope->getCurrentBudget()));
        }
    }

    private function updateEnvelopeProperties(): void
    {
        $this->envelope->setParent($this->parentEnvelope)
            ->setTitle($this->updateEnvelopeDto->getTitle())
            ->setCurrentBudget($this->updateEnvelopeDto->getCurrentBudget())
            ->setTargetBudget($this->updateEnvelopeDto->getTargetBudget())
            ->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateBudgets(float $difference, ?int $oldEnvelopeParentId): void
    {
        if (0.00 !== $difference && $this->parentEnvelope) {
            $this->updateParentCurrentBudget($difference);
        }

        if ($this->envelope->getParent() && $oldEnvelopeParentId !== $this->parentEnvelope?->getId()) {
            $this->updateAncestorsCurrentBudget($this->parentEnvelope, floatval($this->envelope->getCurrentBudget()));
        }
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateParentCurrentBudget(float $difference): void
    {
        $this->updateAncestorsCurrentBudget($this->parentEnvelope, $difference);
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateAncestorsCurrentBudget(?EnvelopeInterface $envelope, float $difference): void
    {
        if (null === $envelope) {
            return;
        }

        $envelope->setCurrentBudget(
            \number_format(
                num: \floatval($envelope->getCurrentBudget()) + $difference,
                decimals: 2,
                thousands_separator: ''
            )
        );

        if ($envelope->getCurrentBudget() > $envelope->getTargetBudget()) {
            throw new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }

        $this->updateAncestorsCurrentBudget($envelope->getParent(), $difference);
    }
}

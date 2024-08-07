<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeInvalidArgumentsException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Validator\CurrentBudgetValidator;
use App\Domain\Envelope\Validator\TargetBudgetValidator;
use App\Domain\User\Entity\UserInterface;

readonly class CreateEnvelopeBuilder implements CreateEnvelopeBuilderInterface
{
    private ?EnvelopeInterface $parentEnvelope;
    private ?CreateEnvelopeDtoInterface $createEnvelopeDto;
    private ?UserInterface $user;

    public function __construct(
        private TargetBudgetValidator $targetBudgetValidator,
        private CurrentBudgetValidator $currentBudgetValidator,
    ) {
    }

    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self
    {
        $this->parentEnvelope = $parentEnvelope;

        return $this;
    }

    public function setCreateEnvelopeDto(CreateEnvelopeDtoInterface $createEnvelopeDto): self
    {
        $this->createEnvelopeDto = $createEnvelopeDto;

        return $this;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeInvalidArgumentsException
     */
    public function build(): EnvelopeInterface
    {
        $this->validateInputs();

        $envelope = $this->createNewEnvelope();

        $this->updateBudgets();

        return $envelope;
    }

    /**
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeInvalidArgumentsException
     */
    private function validateInputs(): void
    {
        if (!$this->createEnvelopeDto || !$this->user) {
            throw new EnvelopeInvalidArgumentsException('CreateEnvelopeDto and User must be set.', 400);
        }

        $this->targetBudgetValidator->validate($this->createEnvelopeDto->getTargetBudget(), $this->parentEnvelope);
        $this->currentBudgetValidator->validate($this->createEnvelopeDto->getCurrentBudget(), $this->parentEnvelope);
    }

    private function createNewEnvelope(): EnvelopeInterface
    {
        $envelope = new Envelope();

        $envelope->setParent($this->parentEnvelope)
            ->setCurrentBudget($this->createEnvelopeDto->getCurrentBudget())
            ->setTargetBudget($this->createEnvelopeDto->getTargetBudget())
            ->setTitle($this->createEnvelopeDto->getTitle())
            ->setCreatedAt(new \DateTimeImmutable('now'))
            ->setUpdatedAt(new \DateTime('now'))
            ->setUser($this->user);

        return $envelope;
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateBudgets(): void
    {
        $currentBudget = floatval($this->createEnvelopeDto->getCurrentBudget());

        if (0.00 !== $currentBudget && $this->parentEnvelope) {
            $this->updateParentCurrentBudget($currentBudget);
        }
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateParentCurrentBudget(float $currentBudget): void
    {
        $this->updateAncestorsCurrentBudget($this->parentEnvelope, $currentBudget);
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateAncestorsCurrentBudget(?EnvelopeInterface $envelope, float $currentBudget): void
    {
        if (null === $envelope) {
            return;
        }

        $envelope->setCurrentBudget(
            \number_format(
                num: \floatval($envelope->getCurrentBudget()) + $currentBudget,
                decimals: 2,
                thousands_separator: ''
            )
        );

        if ($envelope->getCurrentBudget() > $envelope->getTargetBudget()) {
            throw new ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }

        $this->updateAncestorsCurrentBudget($envelope->getParent(), $currentBudget);
    }
}

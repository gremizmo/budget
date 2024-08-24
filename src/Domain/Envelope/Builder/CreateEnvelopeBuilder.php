<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\Domain\Envelope\Validator\CurrentBudgetValidator;
use App\Domain\Envelope\Validator\TargetBudgetValidator;
use App\Domain\Envelope\Validator\TitleValidator;
use App\Domain\User\Entity\UserInterface;

class CreateEnvelopeBuilder implements CreateEnvelopeBuilderInterface
{
    private ?EnvelopeInterface $parentEnvelope = null;
    private CreateEnvelopeDtoInterface $createEnvelopeDto;
    private UserInterface $user;

    public function __construct(
        private readonly TargetBudgetValidator $targetBudgetValidator,
        private readonly CurrentBudgetValidator $currentBudgetValidator,
        private readonly TitleValidator $titleValidator,
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
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeTitleAlreadyExistsForUserException
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenCurrentBudgetExceedsCurrentEnvelopeCurrentBudgetException
     * @throws EnvelopeCurrentBudgetExceedsEnvelopeTargetBudgetException
     */
    public function build(): EnvelopeInterface
    {
        $this->titleValidator->validate($this->createEnvelopeDto->getTitle(), $this->user);
        $this->targetBudgetValidator->validate($this->createEnvelopeDto->getTargetBudget(), $this->parentEnvelope);
        $this->currentBudgetValidator->validate($this->createEnvelopeDto->getCurrentBudget(), $this->createEnvelopeDto->getTargetBudget(), $this->parentEnvelope);

        $envelope = $this->createNewEnvelope();

        $this->updateBudgets();

        return $envelope;
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
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateBudgets(): void
    {
        $currentBudget = floatval($this->createEnvelopeDto->getCurrentBudget());

        if (0.00 !== $currentBudget && $this->parentEnvelope) {
            $this->updateParentCurrentBudget($currentBudget);
        }
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     */
    private function updateParentCurrentBudget(float $currentBudget): void
    {
        $this->updateAncestorsCurrentBudget($this->parentEnvelope, $currentBudget);
    }

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
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
            throw new EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException(EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException::MESSAGE, 400);
        }

        $this->updateAncestorsCurrentBudget($envelope->getParent(), $currentBudget);
    }
}

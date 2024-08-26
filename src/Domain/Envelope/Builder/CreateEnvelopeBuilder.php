<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\Envelope;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\Builder\CreateEnvelopeBuilderException;
use App\Domain\Envelope\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\Domain\Envelope\Validator\CreateEnvelopeTargetBudgetValidator;
use App\Domain\Envelope\Validator\CreateEnvelopeTitleValidator;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Entity\UserInterface;

class CreateEnvelopeBuilder implements CreateEnvelopeBuilderInterface
{
    private ?EnvelopeInterface $parentEnvelope = null;
    private CreateEnvelopeDtoInterface $createEnvelopeDto;
    private UserInterface $user;

    public function __construct(
        private readonly CreateEnvelopeTargetBudgetValidator $targetBudgetValidator,
        private readonly CreateEnvelopeCurrentBudgetValidator $currentBudgetValidator,
        private readonly CreateEnvelopeTitleValidator $titleValidator,
        private readonly LoggerInterface $logger,
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
     * @throws CreateEnvelopeBuilderException
     */
    public function build(): EnvelopeInterface
    {
        try {
            $this->titleValidator->validate($this->createEnvelopeDto->getTitle(), $this->user);
            $this->targetBudgetValidator->validate($this->createEnvelopeDto->getTargetBudget(), $this->parentEnvelope);
            $this->currentBudgetValidator->validate($this->createEnvelopeDto->getCurrentBudget(), $this->createEnvelopeDto->getTargetBudget(), $this->parentEnvelope);
            if ($this->parentEnvelope instanceof EnvelopeInterface && 0.00 !== $currentBudget = floatval($this->createEnvelopeDto->getCurrentBudget())) {
                $this->parentEnvelope->updateAncestorsCurrentBudget($currentBudget);
            }

            return (new Envelope())
                ->setParent($this->parentEnvelope)
                ->setCurrentBudget($this->createEnvelopeDto->getCurrentBudget())
                ->setTargetBudget($this->createEnvelopeDto->getTargetBudget())
                ->setTitle($this->createEnvelopeDto->getTitle())
                ->setCreatedAt(new \DateTimeImmutable('now'))
                ->setUpdatedAt(new \DateTime('now'))
                ->setUser($this->user);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code'      => $exception->getCode(),
            ]);
            throw new CreateEnvelopeBuilderException(CreateEnvelopeBuilderException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}

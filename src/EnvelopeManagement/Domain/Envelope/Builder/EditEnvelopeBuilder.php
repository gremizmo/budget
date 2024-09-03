<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Builder;

use App\EnvelopeManagement\Application\Envelope\Dto\EditEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Exception\SelfParentEnvelopeException;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Validator\EditEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\EditEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\EditEnvelopeTitleValidator;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;

class EditEnvelopeBuilder implements EditEnvelopeBuilderInterface
{
    private EnvelopeInterface $envelope;
    private EditEnvelopeInputInterface $updateEnvelopeDto;
    private ?EnvelopeInterface $newParentEnvelope = null;

    public function __construct(
        private readonly EditEnvelopeTargetBudgetValidator $targetBudgetValidator,
        private readonly EditEnvelopeCurrentBudgetValidator $currentBudgetValidator,
        private readonly EditEnvelopeTitleValidator $titleValidator,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function setEnvelope(EnvelopeInterface $envelope): self
    {
        $this->envelope = $envelope;

        return $this;
    }

    public function setUpdateEnvelopeDto(EditEnvelopeInputInterface $updateEnvelopeDto): self
    {
        $this->updateEnvelopeDto = $updateEnvelopeDto;

        return $this;
    }

    public function setParentEnvelope(?EnvelopeInterface $newParentEnvelope): self
    {
        $this->newParentEnvelope = $newParentEnvelope;

        return $this;
    }

    /**
     * @throws EditEnvelopeBuilderException
     */
    public function build(): EnvelopeInterface
    {
        try {
            if ($this->newParentEnvelope?->getUuid() === $this->envelope->getUuid()) {
                throw new SelfParentEnvelopeException('Envelope cannot be its own parent.', 400);
            }

            $this->titleValidator->validate($this->updateEnvelopeDto->getTitle(), $this->envelope);
            $this->targetBudgetValidator->validate($this->updateEnvelopeDto->getTargetBudget(), $this->envelope, $this->newParentEnvelope);
            $this->currentBudgetValidator->validate($this->updateEnvelopeDto->getCurrentBudget(), $this->updateEnvelopeDto->getTargetBudget(), $this->envelope, $this->newParentEnvelope);
            $oldParentEnvelopeId = $this->envelope->getParent()?->getUuid();

            if ($oldParentEnvelopeId !== $this->newParentEnvelope?->getUuid()) {
                $this->envelope->getParent()?->updateAncestorsCurrentBudget(-floatval($this->envelope->getCurrentBudget()));
            }

            $difference = floatval($this->updateEnvelopeDto->getCurrentBudget()) - floatval($this->envelope->getCurrentBudget());

            if (0.0 !== $difference && $this->newParentEnvelope instanceof EnvelopeInterface) {
                $this->newParentEnvelope->updateAncestorsCurrentBudget($difference);
            }

            if ($this->newParentEnvelope instanceof EnvelopeInterface && $oldParentEnvelopeId !== $this->newParentEnvelope->getUuid()) {
                $this->newParentEnvelope->updateAncestorsCurrentBudget(floatval($this->envelope->getCurrentBudget()));
            }

            return $this->envelope
                ->setParent($this->newParentEnvelope)
                ->setTitle($this->updateEnvelopeDto->getTitle())
                ->setCurrentBudget($this->updateEnvelopeDto->getCurrentBudget())
                ->setTargetBudget($this->updateEnvelopeDto->getTargetBudget())
                ->setUpdatedAt(new \DateTime('now'));
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code'      => $exception->getCode(),
            ]);
            throw new EditEnvelopeBuilderException(EditEnvelopeBuilderException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}

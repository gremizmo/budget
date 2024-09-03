<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Domain\Envelope\Builder;

use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInputInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeCurrentBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTargetBudgetValidator;
use App\EnvelopeManagement\Domain\Envelope\Validator\CreateEnvelopeTitleValidator;
use App\EnvelopeManagement\Domain\Shared\Adapter\LoggerInterface;
use App\EnvelopeManagement\Infrastructure\Shared\Adapter\UuidAdapter;

class CreateEnvelopeBuilder implements CreateEnvelopeBuilderInterface
{
    private ?EnvelopeInterface $parentEnvelope = null;
    private CreateEnvelopeInputInterface $createEnvelopeDto;
    private string $userUuid;

    public function __construct(
        private readonly CreateEnvelopeTargetBudgetValidator $targetBudgetValidator,
        private readonly CreateEnvelopeCurrentBudgetValidator $currentBudgetValidator,
        private readonly CreateEnvelopeTitleValidator $titleValidator,
        private readonly UuidAdapter $uuidAdapter,
        private readonly LoggerInterface $logger,
        private readonly string $envelopeClass,
    ) {
        $model = new $envelopeClass();
        if (!$model instanceof EnvelopeInterface) {
            throw new \RuntimeException('Class should be Envelope in CreateEnvelopeBuilder');
        }
    }

    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self
    {
        $this->parentEnvelope = $parentEnvelope;

        return $this;
    }

    public function setCreateEnvelopeDto(CreateEnvelopeInputInterface $createEnvelopeDto): self
    {
        $this->createEnvelopeDto = $createEnvelopeDto;

        return $this;
    }

    public function setUserUuid(string $userUuid): self
    {
        $this->userUuid = $userUuid;

        return $this;
    }

    /**
     * @throws CreateEnvelopeBuilderException
     */
    public function build(): EnvelopeInterface
    {
        try {
            $this->titleValidator->validate($this->createEnvelopeDto->getTitle(), $this->userUuid);
            $this->currentBudgetValidator->validate($this->createEnvelopeDto->getCurrentBudget(), $this->createEnvelopeDto->getTargetBudget(), $this->parentEnvelope);
            $this->targetBudgetValidator->validate($this->createEnvelopeDto->getTargetBudget(), $this->parentEnvelope);
            if ($this->parentEnvelope instanceof EnvelopeInterface && 0.00 !== $currentBudget = floatval($this->createEnvelopeDto->getCurrentBudget())) {
                $this->parentEnvelope->updateAncestorsCurrentBudget($currentBudget);
            }

            return (new $this->envelopeClass())
                ->setUuid($this->uuidAdapter->generate())
                ->setParent($this->parentEnvelope)
                ->setCurrentBudget($this->createEnvelopeDto->getCurrentBudget())
                ->setTargetBudget($this->createEnvelopeDto->getTargetBudget())
                ->setTitle($this->createEnvelopeDto->getTitle())
                ->setCreatedAt(new \DateTimeImmutable('now'))
                ->setUpdatedAt(new \DateTime('now'))
                ->setUserUuid($this->userUuid);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code'      => $exception->getCode(),
            ]);
            throw new CreateEnvelopeBuilderException(CreateEnvelopeBuilderException::MESSAGE, $exception->getCode(), $exception);
        }
    }
}

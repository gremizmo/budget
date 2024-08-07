<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeInvalidArgumentsException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;

interface EditEnvelopeBuilderInterface
{
    public function setEnvelope(EnvelopeInterface $envelope): self;

    public function setUpdateEnvelopeDto(UpdateEnvelopeDtoInterface $updateEnvelopeDto): self;

    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self;

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeInvalidArgumentsException
     */
    public function build(): EnvelopeInterface;
}

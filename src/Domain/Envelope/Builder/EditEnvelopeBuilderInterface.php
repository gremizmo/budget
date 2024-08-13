<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\Domain\Envelope\Exception\SelfParentEnvelopeException;
use App\Domain\Envelope\Exception\EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;

interface EditEnvelopeBuilderInterface
{
    public function setEnvelope(EnvelopeInterface $envelope): self;

    public function setUpdateEnvelopeDto(UpdateEnvelopeDtoInterface $updateEnvelopeDto): self;

    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self;

    /**
     * @throws EnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws SelfParentEnvelopeException
     * @throws EnvelopeTitleAlreadyExistsForUserException
     */
    public function build(): EnvelopeInterface;
}

<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Builder;

use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeInvalidArgumentsException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\User\Entity\UserInterface;

interface CreateEnvelopeBuilderInterface
{
    public function setParentEnvelope(?EnvelopeInterface $parentEnvelope): self;

    public function setCreateEnvelopeDto(CreateEnvelopeDtoInterface $createEnvelopeDto): self;

    public function setUser(UserInterface $user): self;

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeInvalidArgumentsException
     */
    public function build(): EnvelopeInterface;
}

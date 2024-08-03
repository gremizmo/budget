<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Entity\UserInterface;

readonly class CreateEnvelopeFactory implements CreateEnvelopeFactoryInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     */
    public function createFromDto(
        CreateEnvelopeDtoInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope,
        UserInterface $user,
    ): EnvelopeInterface {
        $builder = new CreateEnvelopeBuilder();
        $builder->setCreateEnvelopeDto($createEnvelopeDto)
            ->setParentEnvelope($parentEnvelope)
            ->setUser($user);

        try {
            return $builder->build();
        } catch (ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException|ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code'      => $exception->getCode(),
            ]);
            throw $exception;
        }
    }
}

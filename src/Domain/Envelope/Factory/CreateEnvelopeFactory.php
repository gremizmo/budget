<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Factory;

use App\Domain\Envelope\Builder\CreateEnvelopeBuilder;
use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Envelope\Exception\ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Envelope\Exception\EnvelopeInvalidArgumentsException;
use App\Domain\Envelope\Exception\ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException;
use App\Domain\Shared\Adapter\LoggerInterface;
use App\Domain\User\Entity\UserInterface;

readonly class CreateEnvelopeFactory implements CreateEnvelopeFactoryInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private CreateEnvelopeBuilder $createEnvelopeBuilder,
    ) {
    }

    /**
     * @throws ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException
     * @throws ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException
     * @throws EnvelopeInvalidArgumentsException
     */
    public function createFromDto(
        CreateEnvelopeDtoInterface $createEnvelopeDto,
        ?EnvelopeInterface $parentEnvelope,
        UserInterface $user,
    ): EnvelopeInterface {
        $this->createEnvelopeBuilder->setCreateEnvelopeDto($createEnvelopeDto)
            ->setParentEnvelope($parentEnvelope)
            ->setUser($user);

        try {
            return $this->createEnvelopeBuilder->build();
        } catch (ChildrenTargetBudgetsExceedsParentEnvelopeTargetBudgetException|ParentEnvelopeCurrentBudgetExceedsParentEnvelopeTargetBudgetException|EnvelopeInvalidArgumentsException $exception) {
            $this->logger->error($exception->getMessage(), [
                'exception' => $exception::class,
                'code'      => $exception->getCode(),
            ]);
            throw $exception;
        }
    }
}

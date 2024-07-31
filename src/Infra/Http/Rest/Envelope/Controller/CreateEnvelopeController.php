<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Controller;

use App\Application\Envelope\Command\CreateEnvelopeCommand;
use App\Application\Envelope\Query\ShowEnvelopeQuery;
use App\Domain\Envelope\Dto\CreateEnvelopeDto;
use App\Domain\Envelope\Entity\EnvelopeInterface;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\User\Entity\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope/new', name: 'app_envelope_new', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
class CreateEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] CreateEnvelopeDto $createEnvelopeDto,
        #[CurrentUser] UserInterface $user,
    ): JsonResponse {
        try {
            $parentEnvelope = $createEnvelopeDto->getParentId() ? $this->queryBus->query(
                new ShowEnvelopeQuery($createEnvelopeDto->getParentId(), $user)
            ) : null;
            $this->commandBus->execute(
                new CreateEnvelopeCommand(
                    $createEnvelopeDto,
                    $user,
                    $parentEnvelope instanceof EnvelopeInterface ? $parentEnvelope : null,
                ),
            );
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process Envelope creation request: '.$exception->getMessage());

            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return $this->json(['message' => 'Envelope creation request received'], Response::HTTP_ACCEPTED);
    }
}

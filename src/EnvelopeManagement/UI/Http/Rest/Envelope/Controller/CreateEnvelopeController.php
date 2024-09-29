<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Envelope\Controller;

use App\EnvelopeManagement\Application\Envelope\Command\CreateEnvelopeCommand;
use App\EnvelopeManagement\Application\Envelope\Dto\CreateEnvelopeInput;
use App\EnvelopeManagement\Application\Envelope\Query\ShowEnvelopeQuery;
use App\EnvelopeManagement\Domain\Envelope\Adapter\CommandBusInterface;
use App\EnvelopeManagement\Domain\Envelope\Adapter\QueryBusInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\SharedContext\Domain\SharedUserInterface;
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
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] CreateEnvelopeInput $createEnvelopeDto,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        $parentEnvelope = $createEnvelopeDto->getParentUuid() ? $this->queryBus->query(
            new ShowEnvelopeQuery($createEnvelopeDto->getParentUuid(), $user->getUuid())
        ) : null;
        $this->commandBus->execute(
            new CreateEnvelopeCommand(
                $createEnvelopeDto,
                $user->getUuid(),
                $parentEnvelope instanceof EnvelopeInterface ? $parentEnvelope : null,
            ),
        );

        return $this->json(['message' => 'Envelope creation request received'], Response::HTTP_OK);
    }
}

<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Presentation\HTTP\Controllers;

use App\EnvelopeManagement\Application\Commands\CreateEnvelopeCommand;
use App\EnvelopeManagement\Domain\Ports\Outbound\CommandBusInterface;
use App\EnvelopeManagement\Presentation\HTTP\DTOs\CreateEnvelopeInput;
use App\SharedContext\Domain\Ports\Inbound\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelopes/new', name: 'app_envelope_new', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
final class CreateEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] CreateEnvelopeInput $createEnvelopeInput,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        $this->commandBus->execute(
            new CreateEnvelopeCommand(
                $createEnvelopeInput->getUuid(),
                $user->getUuid(),
                $createEnvelopeInput->getName(),
                $createEnvelopeInput->getTargetBudget(),
            ),
        );

        return $this->json(['message' => 'Envelope creation request received'], Response::HTTP_OK);
    }
}

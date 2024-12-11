<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Controller;

use App\EnvelopeManagement\Application\Command\CreditEnvelopeCommand;
use App\EnvelopeManagement\Domain\Adapter\CommandBusInterface;
use App\EnvelopeManagement\UI\Http\Dto\CreditEnvelopeInput;
use App\SharedContext\Domain\Model\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelopes/{uuid}/credit', name: 'app_envelope_credit', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
final class CreditEnvelopeController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] CreditEnvelopeInput $creditEnvelopeInput,
        string $uuid,
        #[CurrentUser] SharedUserInterface $user,
    ): JsonResponse {
        $this->commandBus->execute(
            new CreditEnvelopeCommand(
                $creditEnvelopeInput->getCreditMoney(),
                $uuid,
                $user->getUuid(),
            ),
        );

        return $this->json(['message' => 'Envelope credit request received'], Response::HTTP_OK);
    }
}

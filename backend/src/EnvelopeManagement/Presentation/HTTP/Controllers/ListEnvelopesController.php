<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Presentation\HTTP\Controllers;

use App\EnvelopeManagement\Application\Queries\ListEnvelopesQuery;
use App\EnvelopeManagement\Domain\Ports\Outbound\QueryBusInterface;
use App\EnvelopeManagement\Presentation\HTTP\DTOs\ListEnvelopesInput;
use App\SharedContext\Domain\Ports\Inbound\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelopes', name: 'app_envelope_index', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
final class ListEnvelopesController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(
        #[CurrentUser] SharedUserInterface $user,
        #[MapQueryString] ListEnvelopesInput $listEnvelopesDto = new ListEnvelopesInput(),
    ): JsonResponse {
        return $this->json(
            $this->queryBus->query(new ListEnvelopesQuery(
                $user->getUuid(),
                $listEnvelopesDto->getOrderBy(),
                $listEnvelopesDto->getLimit(),
                $listEnvelopesDto->getOffset(),
            )),
            Response::HTTP_OK,
        );
    }
}

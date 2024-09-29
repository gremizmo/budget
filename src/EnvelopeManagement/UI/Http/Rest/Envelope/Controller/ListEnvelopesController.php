<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Envelope\Controller;

use App\EnvelopeManagement\Application\Envelope\Dto\ListEnvelopesInput;
use App\EnvelopeManagement\Application\Envelope\Query\ListEnvelopesQuery;
use App\EnvelopeManagement\Domain\Envelope\Adapter\QueryBusInterface;
use App\SharedContext\Domain\SharedUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/envelope', name: 'app_envelope_index', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
class ListEnvelopesController extends AbstractController
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
            $this->queryBus->query(new ListEnvelopesQuery($user->getUuid(), $listEnvelopesDto)),
            Response::HTTP_OK,
        );
    }
}

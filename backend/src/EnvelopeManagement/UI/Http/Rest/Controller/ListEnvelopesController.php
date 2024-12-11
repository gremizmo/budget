<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\UI\Http\Rest\Controller;

use App\EnvelopeManagement\Application\Query\ListEnvelopesQuery;
use App\EnvelopeManagement\Domain\Adapter\QueryBusInterface;
use App\EnvelopeManagement\UI\Http\Dto\ListEnvelopesInput;
use App\SharedContext\Domain\Model\SharedUserInterface;
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
            $this->queryBus->query(new ListEnvelopesQuery($user->getUuid(), $listEnvelopesDto)),
            Response::HTTP_OK,
        );
    }
}

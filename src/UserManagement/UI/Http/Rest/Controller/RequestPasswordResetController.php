<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\Controller;

use App\UserManagement\Application\Command\RequestPasswordResetCommand;
use App\UserManagement\Application\Dto\RequestPasswordResetInput;
use App\UserManagement\Application\Query\ShowUserQuery;
use App\UserManagement\Domain\Adapter\CommandBusInterface;
use App\UserManagement\Domain\Adapter\QueryBusInterface;
use App\UserManagement\Infrastructure\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user/request-reset-password', name: 'app_user_request_reset_password', methods: ['POST'])]
class RequestPasswordResetController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(#[MapRequestPayload] RequestPasswordResetInput $requestPasswordResetDto): JsonResponse
    {
        $user = $this->queryBus->query(new ShowUserQuery($requestPasswordResetDto->getEmail()));

        if (!$user instanceof User) {
            throw new \Exception('User not found', Response::HTTP_NOT_FOUND);
        }

        $this->commandBus->execute(new RequestPasswordResetCommand($user));

        return $this->json(['message' => 'Password reset email sent'], Response::HTTP_OK);
    }
}
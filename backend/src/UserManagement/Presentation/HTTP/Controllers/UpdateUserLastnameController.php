<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\Controllers;

use App\UserManagement\Application\Commands\UpdateUserLastnameCommand;
use App\UserManagement\Domain\Ports\Inbound\UserViewInterface;
use App\UserManagement\Domain\Ports\Outbound\CommandBusInterface;
use App\UserManagement\Presentation\HTTP\DTOs\UpdateUserLastnameInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/users/lastname', name: 'app_user_edit_lastname', methods: ['PUT'])]
#[IsGranted('ROLE_USER')]
final class UpdateUserLastnameController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(
        #[CurrentUser] UserViewInterface $currentUser,
        #[MapRequestPayload] UpdateUserLastnameInput $updateUserLastnameInput,
    ): JsonResponse {
        $this->commandBus->execute(
            new UpdateUserLastnameCommand(
                $currentUser->getUuid(),
                $updateUserLastnameInput->getLastname(),
            ),
        );

        return $this->json(['message' => 'Lastname change request processed successfully'], Response::HTTP_OK);
    }
}

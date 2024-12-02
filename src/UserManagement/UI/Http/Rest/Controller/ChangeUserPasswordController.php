<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\Controller;

use App\UserManagement\Application\Command\ChangeUserPasswordCommand;
use App\UserManagement\Application\Dto\ChangeUserPasswordInput;
use App\UserManagement\Domain\Adapter\CommandBusInterface;
use App\UserManagement\Infrastructure\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user/{uuid}/change-password', name: 'app_user_change_password', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
class ChangeUserPasswordController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(
        string $uuid,
        #[CurrentUser] User $currentUser,
        #[MapRequestPayload] ChangeUserPasswordInput $changePasswordDto,
    ): JsonResponse {
        if ($uuid !== $currentUser->getUuid()) {
            throw new \Exception('An error occurred on change user password in ChangeUserPasswordController', Response::HTTP_FORBIDDEN);
        }
        $this->commandBus->execute(new ChangeUserPasswordCommand(
            $changePasswordDto,
            $currentUser,
        ));

        return $this->json(['message' => 'Password change request processed successfully'], Response::HTTP_OK);
    }
}

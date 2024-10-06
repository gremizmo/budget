<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\User\Controller;

use App\UserManagement\Application\User\Command\ChangeUserPasswordCommand;
use App\UserManagement\Application\User\Dto\ChangeUserPasswordInput;
use App\UserManagement\Domain\User\Adapter\CommandBusInterface;
use App\UserManagement\Infrastructure\User\Entity\User;
use Psr\Log\LoggerInterface;
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
        private readonly LoggerInterface $logger,
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
            $this->logger->error('Failed to process User changePassword request: User not allowed to access this resource');

            throw new \Exception('An error occurred on change user password in ChangeUserPasswordController', Response::HTTP_FORBIDDEN);
        }
        $this->commandBus->execute(new ChangeUserPasswordCommand(
            $changePasswordDto,
            $currentUser,
        ));

        return $this->json(['message' => 'Password change request processed successfully'], Response::HTTP_OK);
    }
}

<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Controller;

use App\Application\User\Command\ChangeUserPasswordCommand;
use App\Application\User\Dto\ChangeUserPasswordInput;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Infra\Http\Rest\User\Entity\User;
use App\Infra\Http\Rest\User\Exception\ChangeUserPasswordControllerException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user/{id}/change-password', name: 'app_user_change_password', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
class ChangeUserPasswordController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(
        User $user,
        #[CurrentUser] User $currentUser,
        #[MapRequestPayload] ChangeUserPasswordInput $changePasswordDto,
    ): JsonResponse {
        if ($user->getId() !== $currentUser->getId()) {
            $this->logger->error('Failed to process User changePassword request: User not allowed to access this resource');
            throw new ChangeUserPasswordControllerException(ChangeUserPasswordControllerException::MESSAGE, Response::HTTP_FORBIDDEN);
        }
        try {
            $this->commandBus->execute(new ChangeUserPasswordCommand(
                $changePasswordDto,
                $currentUser,
            ));
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process password change request: '.$exception->getMessage());
            throw new ChangeUserPasswordControllerException(ChangeUserPasswordControllerException::MESSAGE, Response::HTTP_FORBIDDEN);
        }

        return $this->json(['message' => 'Password change request processed successfully'], Response::HTTP_OK);
    }
}

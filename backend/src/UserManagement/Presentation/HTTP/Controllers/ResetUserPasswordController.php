<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\Controllers;

use App\UserManagement\Application\Commands\ResetUserPasswordCommand;
use App\UserManagement\Domain\Ports\Outbound\CommandBusInterface;
use App\UserManagement\Presentation\HTTP\DTOs\ResetUserPasswordInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users/reset-password', name: 'app_user_reset_password', methods: ['POST'])]
final class ResetUserPasswordController extends AbstractController
{
    public function __construct(private readonly CommandBusInterface $commandBus)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(#[MapRequestPayload] ResetUserPasswordInput $resetUserPasswordDto): JsonResponse
    {
        $this->commandBus->execute(
            new ResetUserPasswordCommand(
                $resetUserPasswordDto->getToken(),
                $resetUserPasswordDto->getNewPassword(),
            ),
        );

        return $this->json(['message' => 'Password was reset'], Response::HTTP_OK);
    }
}

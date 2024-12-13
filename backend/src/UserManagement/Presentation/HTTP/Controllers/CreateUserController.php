<?php

declare(strict_types=1);

namespace App\UserManagement\Presentation\HTTP\Controllers;

use App\UserManagement\Application\Commands\CreateUserCommand;
use App\UserManagement\Domain\Ports\Outbound\CommandBusInterface;
use App\UserManagement\Presentation\HTTP\DTOs\CreateUserInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users/new', name: 'app_user_new', methods: ['POST'])]
final class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(#[MapRequestPayload] CreateUserInput $createUserDto): JsonResponse
    {
        $this->commandBus->execute(
            new CreateUserCommand(
                $createUserDto->getUuid(),
                $createUserDto->getEmail(),
                $createUserDto->getPassword(),
                $createUserDto->getFirstname(),
                $createUserDto->getLastname(),
                $createUserDto->isConsentGiven(),
            ),
        );

        return $this->json(['message' => 'User creation request received'], Response::HTTP_ACCEPTED);
    }
}

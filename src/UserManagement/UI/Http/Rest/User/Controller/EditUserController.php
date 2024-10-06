<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\User\Controller;

use App\UserManagement\Application\User\Command\EditUserCommand;
use App\UserManagement\Application\User\Dto\EditUserInput;
use App\UserManagement\Domain\User\Adapter\CommandBusInterface;
use App\UserManagement\Domain\User\Model\UserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user/{uuid}', name: 'app_user_edit', methods: ['PUT'])]
#[IsGranted('ROLE_USER')]
class EditUserController extends AbstractController
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
        #[CurrentUser] UserInterface $currentUser,
        #[MapRequestPayload] EditUserInput $editUserDto,
    ): JsonResponse {
        if ($uuid !== $currentUser->getUuid()) {
            $this->logger->error('Failed to process User edit request: User not allowed to access this resource');

            throw new \Exception('User not allowed to access this resource', Response::HTTP_FORBIDDEN);
        }

        $this->commandBus->execute(new EditUserCommand($currentUser, $editUserDto));

        return $this->json($editUserDto, Response::HTTP_OK);
    }
}

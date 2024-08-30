<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Controller;

use App\Application\User\Command\EditUserCommand;
use App\Application\User\Dto\EditUserInput;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\Shared\Model\UserInterface;
use App\Infra\Http\Rest\User\Exception\EditUserControllerException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/user/{id}', name: 'app_user_edit', methods: ['PUT'])]
#[IsGranted('ROLE_USER')]
class EditUserController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(
        int $id,
        #[CurrentUser] UserInterface $currentUser,
        #[MapRequestPayload] EditUserInput $editUserDto,
    ): JsonResponse {
        if ($id !== $currentUser->getId()) {
            $this->logger->error('Failed to process User edit request: User not allowed to access this resource');
            throw new EditUserControllerException('User not allowed to access this resource', Response::HTTP_FORBIDDEN);
        }

        try {
            $this->commandBus->execute(new EditUserCommand($currentUser, $editUserDto));
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process User edit request: '.$exception->getMessage());
            throw new EditUserControllerException(EditUserControllerException::MESSAGE, $exception->getCode(), $exception);
        }

        return $this->json($editUserDto, Response::HTTP_OK);
    }
}

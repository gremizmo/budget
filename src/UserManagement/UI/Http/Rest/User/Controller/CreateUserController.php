<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\User\Controller;

use App\UserManagement\Application\User\Command\CreateUserCommand;
use App\UserManagement\Application\User\Dto\CreateUserInput;
use App\UserManagement\Application\User\Query\GetUserAlreadyExistsQuery;
use App\UserManagement\Domain\Shared\Adapter\CommandBusInterface;
use App\UserManagement\Domain\Shared\Adapter\QueryBusInterface;
use App\UserManagement\Domain\User\Model\UserInterface;
use App\UserManagement\UI\Http\Rest\User\Exception\CreateUserControllerException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user/new', name: 'app_user_new', methods: ['POST'])]
class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(#[MapRequestPayload] CreateUserInput $createUserDto): JsonResponse
    {
        try {
            if (!$this->queryBus->query(new GetUserAlreadyExistsQuery($createUserDto->getEmail())) instanceof UserInterface) {
                $this->commandBus->execute(new CreateUserCommand($createUserDto));
            } else {
                $this->logger->error('Failed to process User creation request: User already exists');
                throw new CreateUserControllerException('User already exists', Response::HTTP_CONFLICT);
            }
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process User creation request: '.$exception->getMessage());
            throw new CreateUserControllerException(CreateUserControllerException::MESSAGE, $exception->getCode(), $exception);
        }

        return $this->json(['message' => 'User creation request received'], Response::HTTP_ACCEPTED);
    }
}
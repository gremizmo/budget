<?php

declare(strict_types=1);

namespace App\UserManagement\UI\Http\Rest\User\Controller;

use App\UserManagement\Application\User\Command\CreateUserCommand;
use App\UserManagement\Application\User\Dto\CreateUserInput;
use App\UserManagement\Application\User\Query\GetUserAlreadyExistsQuery;
use App\UserManagement\Domain\User\Adapter\CommandBusInterface;
use App\UserManagement\Domain\User\Adapter\QueryBusInterface;
use App\UserManagement\Domain\User\Model\UserInterface;
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

    /**
     * @throws \Exception
     */
    public function __invoke(#[MapRequestPayload] CreateUserInput $createUserDto): JsonResponse
    {
        if (!$this->queryBus->query(new GetUserAlreadyExistsQuery($createUserDto->getEmail())) instanceof UserInterface) {
            $this->commandBus->execute(new CreateUserCommand($createUserDto));
        } else {
            $this->logger->error('Failed to process User creation request: User already exists');

            throw new \Exception('User already exists', Response::HTTP_CONFLICT);
        }

        return $this->json(['message' => 'User creation request received'], Response::HTTP_ACCEPTED);
    }
}

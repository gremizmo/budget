<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\User\Controller;

use App\Application\User\Command\CreateUserCommand;
use App\Application\User\Query\GetUserAlreadyExistsQuery;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\User\Dto\CreateUserDto;
use App\Domain\Shared\Adapter\CommandBusInterface;
use App\Domain\User\Entity\UserInterface;
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

    public function __invoke(#[MapRequestPayload] CreateUserDto $createUserDto): JsonResponse
    {
        try {
            if (!$this->queryBus->query(new GetUserAlreadyExistsQuery($createUserDto->getEmail())) instanceof UserInterface) {
                $this->commandBus->execute(new CreateUserCommand($createUserDto));
            } else {
                $this->logger->error('Failed to process User creation request: User already exists');

                return $this->json([
                    'error' => 'Failed to process User creation request: User already exists',
                ], Response::HTTP_CONFLICT);
            }
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to process User creation request: '.$exception->getMessage());

            $exceptionType = \strrchr($exception::class, '\\');

            return $this->json([
                'error' => $exception->getMessage(),
                'type' => \substr(\is_string($exceptionType) ? $exceptionType : '', 1),
                'code' => $exception->getCode(),
            ], $exception->getCode());
        }

        return $this->json(['message' => 'User creation request received'], Response::HTTP_ACCEPTED);
    }
}

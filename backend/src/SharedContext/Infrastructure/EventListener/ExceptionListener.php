<?php

declare(strict_types=1);

namespace App\SharedContext\Infrastructure\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class ExceptionListener
{
    public function __construct()
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $exceptionCode = $exception instanceof ValidationFailedException ? 400 : $exception->getCode();
        $type = \strrchr($exception::class, '\\');
        $event->setResponse(new JsonResponse([
            'type' => \substr(\is_string($type) ? $type : '', 1),
            'message' => $exception->getMessage(),
        ], 0 === $exceptionCode ? 500 : $exceptionCode));
    }
}

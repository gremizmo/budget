<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

readonly class ExceptionListener
{
    public function __construct()
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable()->getPrevious();

        if ($exception instanceof \Throwable) {
            $type = \strrchr($exception::class, '\\');
            $event->setResponse(new JsonResponse([
                'type' => \substr(\is_string($type) ? $type : '', 1),
                'message' => $exception->getMessage(),
            ], $exception->getCode()));
        }
    }
}

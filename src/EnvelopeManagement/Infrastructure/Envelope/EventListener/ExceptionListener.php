<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

readonly class ExceptionListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->logger->error('An error occurred: '.$exception->getMessage(), ['exception' => $exception]);

        $previousExceptions = [];
        $exceptionCopy = $exception;
        $type = '';

        while (null !== $exceptionCopy) {
            if (null === $exceptionCopy->getPrevious()) {
                $type = \strrchr($exceptionCopy::class, '\\');
                break;
            }
            $exceptionCopy = $exceptionCopy->getPrevious();
            $previousExceptions[] = $exceptionCopy->getMessage();
        }

        $response = new JsonResponse([
            'errors' => $previousExceptions,
            'type' => \substr(\is_string($type) ? $type : '', 1),
        ], Response::HTTP_BAD_REQUEST);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}

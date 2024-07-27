<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Event;

interface EventInterface
{
    public function getEnvelopeId(): int;
    /**
     * @return array{
     *     title?: array{
     *         new?: string,
     *         old?: string
     *     },
     *     updatedAt?: array{
     *         new?: array{
     *             date?: string,
     *             timezone?: string,
     *             timezone_type?: int
     *         },
     *         old?: array{
     *             date?: string,
     *             timezone?: string,
     *             timezone_type?: int
     *         }
     *     },
     *     updatedBy?: array{
     *         new?: string,
     *         old?: string
     *     },
     *     targetBudget?: array{
     *         new?: string,
     *         old?: string
     *     },
     *     currentBudget?: array{
     *         new?: string,
     *         old?: string
     *     }
     * }
     */
    public function getChanges(): array;
    public function getOccurredOn(): \DateTimeImmutable;
}

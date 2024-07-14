<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Event;

class EnvelopeUpdatedEvent implements EventInterface
{
    private \DateTimeImmutable $occurredOn;

    /**
     * @param array{
     *      title?: array{
     *          new?: string,
     *          old?: string
     *      },
     *      updatedAt?: array{
     *          new?: array{
     *              date?: string,
     *              timezone?: string,
     *              timezone_type?: int
     *          },
     *          old?: array{
     *              date?: string,
     *              timezone?: string,
     *              timezone_type?: int
     *          }
     *      },
     *      updatedBy?: array{
     *          new?: string,
     *          old?: string
     *      },
     *      targetBudget?: array{
     *          new?: string,
     *          old?: string
     *      },
     *      currentBudget?: array{
     *          new?: string,
     *          old?: string
     *      }
     *  } $changes
     */
    public function __construct(private readonly int $envelopeId, private readonly array $changes)
    {
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getEnvelopeId(): int
    {
        return $this->envelopeId;
    }

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
    public function getChanges(): array
    {
        return $this->changes;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}

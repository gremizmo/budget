<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Entity;

class EnvelopeHistory
{
    private int $id;
    private int $envelopeId;
    private \DateTimeImmutable $updatedAt;
    private string $updatedBy;
    private array $changes;

    public function __construct(
        int $envelopeId,
        string $updatedBy,
        array $changes
    ) {
        $this->envelopeId = $envelopeId;
        $this->updatedAt = new \DateTimeImmutable();
        $this->updatedBy = $updatedBy;
        $this->changes = $changes;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEnvelopeId(): int
    {
        return $this->envelopeId;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): string
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
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

    public function setChanges(array $changes): self
    {
        $this->changes = $changes;

        return $this;
    }
}

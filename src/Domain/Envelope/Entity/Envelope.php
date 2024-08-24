<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Entity;

use App\Domain\User\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;

class Envelope implements EnvelopeInterface
{
    private int $id;
    private \DateTimeImmutable $createdAt;
    private \DateTime $updatedAt;
    private string $currentBudget = '0.00';
    private string $targetBudget = '0.00';
    private string $title = '';
    private ?EnvelopeInterface $parent = null;
    private Collection $children;
    private UserInterface $user;

    public function __construct()
    {
        $this->children = new EnvelopeCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
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

    public function getParent(): ?EnvelopeInterface
    {
        return $this->parent;
    }

    public function setParent(?EnvelopeInterface $parent = null): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCurrentBudget(): string
    {
        return $this->currentBudget;
    }

    public function setCurrentBudget(string $currentBudget): self
    {
        $this->currentBudget = $currentBudget;

        return $this;
    }

    public function getTargetBudget(): string
    {
        return $this->targetBudget;
    }

    public function setTargetBudget(string $targetBudget): self
    {
        $this->targetBudget = $targetBudget;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setChildren(EnvelopeCollectionInterface $envelopes): self
    {
        $this->children = $envelopes;

        return $this;
    }

    public function addChild(EnvelopeInterface $child): self
    {
        if (!$this->getChildren()->contains($child)) {
            $this->getChildren()->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }
}

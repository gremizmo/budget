<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Entity;

use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Envelope\Model\EnvelopeModel;
use App\Domain\User\Entity\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Envelope extends EnvelopeModel
{
    protected int $id;
    protected \DateTimeImmutable $createdAt;
    protected \DateTime $updatedAt;
    protected string $currentBudget = '0.00';
    protected string $targetBudget = '0.00';
    protected string $title = '';
    protected ?EnvelopeInterface $parent = null;
    protected \ArrayAccess|\IteratorAggregate|\Serializable|\Countable $children;
    protected UserInterface $user;

    public function __construct()
    {
        parent::__construct();
        $this->children = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
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
        $this->getParent()?->setUpdatedAt($updatedAt);

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

    public function getChildren(): \ArrayAccess|\IteratorAggregate|\Serializable|\Countable
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

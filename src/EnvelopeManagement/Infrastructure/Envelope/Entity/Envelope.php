<?php

declare(strict_types=1);

namespace App\EnvelopeManagement\Infrastructure\Envelope\Entity;

use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeInterface;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\EnvelopeManagement\Infrastructure\Envelope\Repository\EnvelopeCommandRepository')]
#[ORM\Table(name: 'envelope')]
class Envelope extends EnvelopeModel
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    protected \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime')]
    protected \DateTime $updatedAt;

    #[ORM\Column(name: 'current_budget', type: 'string')]
    protected string $currentBudget = '0.00';

    #[ORM\Column(name: 'target_budget', type: 'string')]
    protected string $targetBudget = '0.00';

    #[ORM\Column(name: 'title', type: 'string', length: 255)]
    protected string $title = '';

    #[ORM\ManyToOne(targetEntity: 'App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope', inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true)]
    protected ?EnvelopeInterface $parent = null;

    #[ORM\OneToMany(targetEntity: 'App\EnvelopeManagement\Infrastructure\Envelope\Entity\Envelope', mappedBy: 'parent', cascade: ['persist', 'remove'])]
    protected \ArrayAccess|\IteratorAggregate|\Serializable|\Countable $children;

    #[ORM\Column(name: 'user_id', type: 'integer')]
    protected int $userId;

    public function __construct()
    {
        $this->children = new ArrayCollection();
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

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function addChild(EnvelopeInterface $child): self
    {
        $this->children->add($child);

        return $this;
    }
}

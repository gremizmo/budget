<?php

declare(strict_types=1);

namespace App\Domain\Envelope\Entity;

use App\Domain\User\Entity\UserInterface;

interface EnvelopeInterface
{
    public function getId(): int;

    public function getParent(): ?EnvelopeInterface;

    public function setParent(?EnvelopeInterface $parent = null): self;

    public function getCreatedAt(): \DateTimeImmutable;

    public function setCreatedAt(\DateTimeImmutable $createdAt): self;

    public function getUpdatedAt(): \DateTime;

    public function setUpdatedAt(\DateTime $updatedAt): self;

    public function getCurrentBudget(): string;

    public function setCurrentBudget(string $currentBudget): self;

    public function getTargetBudget(): string;

    public function setTargetBudget(string $targetBudget): self;

    public function getTitle(): string;

    public function setTitle(string $title): self;

    public function setChildren(EnvelopeCollectionInterface $envelopes): self;

    public function addChild(EnvelopeInterface $child): self;

    public function getChildren(): EnvelopeCollectionInterface|iterable;

    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): self;
}

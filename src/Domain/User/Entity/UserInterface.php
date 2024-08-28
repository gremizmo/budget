<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Envelope\Entity\EnvelopeCollectionInterface;
use App\Domain\Envelope\Model\EnvelopeInterface;

interface UserInterface
{
    public function getId(): int;

    public function setId(int $id): self;

    public function getEmail(): string;

    public function setEmail(string $email): self;

    public function getPassword(): string;

    public function setPassword(string $password): self;

    public function getFirstname(): string;

    public function setFirstname(string $firstname): self;

    public function getLastname(): string;

    public function setLastname(string $lastname): self;

    public function isConsentGiven(): bool;

    public function setConsentGiven(bool $consentGiven): self;

    /**
     * @return array<string>
     */
    public function getRoles(): array;

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): self;

    public function getConsentDate(): \DateTimeImmutable;

    public function setConsentDate(\DateTimeImmutable $consentDate): self;

    public function getEnvelopes(): EnvelopeCollectionInterface;

    public function setEnvelopes(EnvelopeCollectionInterface $envelopes): self;

    public function getCreatedAt(): \DateTimeImmutable;

    public function setCreatedAt(\DateTimeImmutable $createdAt): UserInterface;

    public function getUpdatedAt(): \DateTime;

    public function setUpdatedAt(\DateTime $updatedAt): UserInterface;

    public function addEnvelope(EnvelopeInterface $envelope): self;

    public function eraseCredentials(): void;

    public function getUserIdentifier(): string;

    public function getPasswordResetToken(): ?string;

    public function setPasswordResetToken(?string $passwordResetToken): self;

    public function getPasswordResetTokenExpiry(): ?\DateTimeImmutable;

    public function setPasswordResetTokenExpiry(?\DateTimeImmutable $passwordResetTokenExpiry): self;
}

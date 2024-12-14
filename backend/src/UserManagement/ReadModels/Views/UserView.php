<?php

declare(strict_types=1);

namespace App\UserManagement\ReadModels\Views;

use App\SharedContext\Domain\Ports\Inbound\SharedUserInterface;
use App\UserManagement\Domain\Ports\Inbound\UserViewInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: 'App\UserManagement\Infrastructure\Persistence\Repositories\UserRepository')]
#[ORM\Table(name: 'user_view')]
final class UserView implements UserViewInterface, UserInterface, PasswordAuthenticatedUserInterface, SharedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[ORM\Column(name: 'uuid', type: 'string', length: 100, unique: true)]
    protected string $uuid;

    #[ORM\Column(name: 'email', type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(name: 'password', type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(name: 'firstname', type: 'string', length: 255)]
    private string $firstname;

    #[ORM\Column(name: 'lastname', type: 'string', length: 255)]
    private string $lastname;

    #[ORM\Column(name: 'consent_given', type: 'boolean')]
    private bool $consentGiven;

    #[ORM\Column(name: 'consent_date', type: 'datetime_immutable')]
    private \DateTimeImmutable $consentDate;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime')]
    private \DateTime $updatedAt;

    /**
     * @var array<string> $roles
     */
    #[ORM\Column(name: 'roles', type: 'json')]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(name: 'is_deleted', type: 'boolean', nullable: false)]
    private bool $isDeleted = false;

    #[ORM\Column(name: 'password_reset_token', type: 'string', length: 64, nullable: true)]
    private ?string $passwordResetToken = null;

    #[ORM\Column(name: 'password_reset_token_expiry', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $passwordResetTokenExpiry = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
        $this->consentDate = new \DateTimeImmutable();
    }

    #[\Override]
    public function getId(): int
    {
        return $this->id;
    }

    #[\Override]
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    #[\Override]
    public function getUuid(): string
    {
        return $this->uuid;
    }

    #[\Override]
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    #[\Override]
    public function getEmail(): string
    {
        return $this->email;
    }

    #[\Override]
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    #[\Override]
    public function getPassword(): string
    {
        return $this->password;
    }

    #[\Override]
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    #[\Override]
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    #[\Override]
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    #[\Override]
    public function getLastname(): string
    {
        return $this->lastname;
    }

    #[\Override]
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    #[\Override]
    public function isConsentGiven(): bool
    {
        return $this->consentGiven;
    }

    #[\Override]
    public function setConsentGiven(bool $consentGiven): self
    {
        $this->consentGiven = $consentGiven;

        return $this;
    }

    #[\Override]
    public function getConsentDate(): \DateTimeImmutable
    {
        return $this->consentDate;
    }

    /**
     * @return array<string>
     */
    #[\Override]
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array<string> $roles
     */
    #[\Override]
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    #[\Override]
    public function setConsentDate(\DateTimeImmutable $consentDate): self
    {
        $this->consentDate = $consentDate;

        return $this;
    }

    #[\Override]
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[\Override]
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[\Override]
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    #[\Override]
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[\Override]
    public function eraseCredentials(): void
    {
    }

    #[\Override]
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    #[\Override]
    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    #[\Override]
    public function setPasswordResetToken(?string $passwordResetToken): self
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }

    #[\Override]
    public function getPasswordResetTokenExpiry(): ?\DateTimeImmutable
    {
        return $this->passwordResetTokenExpiry;
    }

    #[\Override]
    public function setPasswordResetTokenExpiry(?\DateTimeImmutable $passwordResetTokenExpiry): self
    {
        $this->passwordResetTokenExpiry = $passwordResetTokenExpiry;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): UserView
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}

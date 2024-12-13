<?php

namespace App\UserManagement\Domain\Aggregates;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;
use App\UserManagement\Domain\Events\UserCreatedEvent;
use App\UserManagement\Domain\Events\UserFirstnameUpdatedEvent;
use App\UserManagement\Domain\Events\UserLastnameUpdatedEvent;
use App\UserManagement\Domain\Events\UserPasswordResetEvent;
use App\UserManagement\Domain\Events\UserPasswordResetRequestedEvent;
use App\UserManagement\Domain\Events\UserPasswordUpdatedEvent;
use App\UserManagement\Domain\Exceptions\InvalidUserOperationException;
use App\UserManagement\Domain\Exceptions\UserAlreadyExistsException;
use App\UserManagement\Domain\Ports\Inbound\UserRepositoryInterface;
use App\UserManagement\Domain\ValueObjects\Email;
use App\UserManagement\Domain\ValueObjects\Firstname;
use App\UserManagement\Domain\ValueObjects\UserId;
use App\UserManagement\Domain\ValueObjects\Lastname;
use App\UserManagement\Domain\ValueObjects\Password;

final class User
{
    private UserId $userId;

    private Email $email;

    private Password $password;

    private Firstname $firstname;

    private Lastname $lastname;

    private bool $consentGiven;

    private \DateTimeImmutable $consentDate;

    private \DateTimeImmutable $createdAt;

    private \DateTime $updatedAt;

    private array $roles;

    private ?string $passwordResetToken;

    private ?\DateTimeImmutable $passwordResetTokenExpiry;

    private bool $isDeleted;

    private array $uncommittedEvents = [];

    private function __construct()
    {
        $this->email = Email::create('init@mail.com');
        $this->password = Password::create('init');
        $this->firstname = Firstname::create('init');
        $this->lastname = Lastname::create('init');
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTimeImmutable();
        $this->consentGiven = true;
        $this->consentDate = new \DateTimeImmutable();
        $this->roles = ['ROLE_USER'];
        $this->passwordResetToken = null;
        $this->passwordResetTokenExpiry = null;
        $this->isDeleted = false;
    }

    public static function reconstituteFromEvents(array $events): self
    {
        $aggregate = new self();

        foreach ($events as $event) {
            $aggregate->applyEvent($event['type']::fromArray(json_decode($event['payload'], true)));
        }

        return $aggregate;
    }

    public static function create(
        string $userId,
        string $email,
        string $password,
        string $firstname,
        string $lastname,
        bool $isConsentGiven,
        UserRepositoryInterface $userRepository,
    ): self {
        if ($userRepository->findOneBy(['email' => $email, 'isDeleted' => false])) {
            throw new UserAlreadyExistsException(UserAlreadyExistsException::MESSAGE, 400);
        }

        $aggregate = new self();

        $event = new UserCreatedEvent(
            $userId,
            $email,
            $password,
            $firstname,
            $lastname,
            $isConsentGiven,
            $aggregate->roles,
        );

        $aggregate->applyEvent($event);
        $aggregate->recordEvent($event);

        return $aggregate;
    }

    public function updateFirstname(Firstname $firstname, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $event = new UserFirstnameUpdatedEvent(
            $this->userId->__toString(),
            $firstname->__toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function updateLastname(Lastname $lastname, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $event = new UserLastnameUpdatedEvent(
            $this->userId->__toString(),
            $lastname->__toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function updatePassword(Password $oldPassword, Password $newPassword, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $event = new UserPasswordUpdatedEvent(
            $this->userId->__toString(),
            $oldPassword->__toString(),
            $newPassword->__toString()
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function setPasswordResetToken(Password $passwordResetToken, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        $event = new UserPasswordResetRequestedEvent(
            $this->userId->__toString(),
            $passwordResetToken->__toString(),
            new \DateTimeImmutable('+1 hour'),
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function resetPassword(Password $password, UserId $userId): void
    {
        $this->assertNotDeleted();
        $this->assertOwnership($userId);

        if ($this->passwordResetTokenExpiry < new \DateTimeImmutable()) {
            throw InvalidUserOperationException::operationOnResetUserPassword();
        }

        $event = new UserPasswordResetEvent(
            $this->userId->__toString(),
            $password->__toString(),
        );

        $this->applyEvent($event);
        $this->recordEvent($event);
    }

    public function getUncommittedEvents(): array
    {
        return $this->uncommittedEvents;
    }

    public function clearUncommitedEvent(): void
    {
        $this->uncommittedEvents = [];
    }

    private function applyEvent(EventInterface $event): void
    {
        match (get_class($event)) {
            UserCreatedEvent::class => $this->applyCreatedEvent($event),
            UserFirstnameUpdatedEvent::class => $this->applyFirstnameUpdated($event),
            UserLastnameUpdatedEvent::class => $this->applyLastnameUpdated($event),
            UserPasswordUpdatedEvent::class => $this->applyUserPasswordUpdated($event),
            UserPasswordResetRequestedEvent::class => $this->applyUserPasswordResetRequested($event),
            UserPasswordResetEvent::class => $this->applyUserPasswordReset($event),
            default => throw new \RuntimeException(sprintf('Unsupported event type: %s', get_class($event))),
        };
    }

    private function applyCreatedEvent(UserCreatedEvent $event): void
    {
        $this->userId = UserId::create($event->getAggregateId());
        $this->email = Email::create($event->getEmail());
        $this->password = Password::create($event->getPassword());
        $this->firstname = Firstname::create($event->getFirstname());
        $this->lastname = Lastname::create($event->getLastname());
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTimeImmutable();
        $this->consentGiven = $event->isConsentGiven();
        $this->consentDate = new \DateTimeImmutable();
        $this->roles = ['ROLE_USER'];
        $this->passwordResetToken = null;
        $this->passwordResetTokenExpiry = null;
        $this->isDeleted = false;
    }

    private function applyFirstnameUpdated(UserFirstnameUpdatedEvent $event): void
    {
        $this->firstname = Firstname::create($event->getFirstname());
        $this->updatedAt = new \DateTime();
    }

    private function applyLastnameUpdated(UserLastnameUpdatedEvent $event): void
    {
        $this->lastname = Lastname::create($event->getLastname());
        $this->updatedAt = new \DateTime();
    }

    private function applyUserPasswordUpdated(UserPasswordUpdatedEvent $event): void
    {
        $this->password = Password::create($event->getNewPassword());
        $this->updatedAt = new \DateTime();
    }

    private function applyUserPasswordResetRequested(UserPasswordResetRequestedEvent $event): void
    {
        $this->passwordResetToken = Password::create($event->getPasswordResetToken());
        $this->passwordResetTokenExpiry = $event->getPasswordResetTokenExpiry();
        $this->updatedAt = new \DateTime();
    }

    private function applyUserPasswordReset(UserPasswordResetEvent $event): void
    {
        $this->password = Password::create($event->getPassword());
        $this->updatedAt = new \DateTime();
    }

    private function assertOwnership(UserId $userId): void
    {
        if (!$this->userId->equals($userId)) {
            throw new \RuntimeException('User does not have permission to access this user.');
        }
    }

    private function assertNotDeleted(): void
    {
        if ($this->isDeleted) {
            throw InvalidUserOperationException::operationOnDeletedUser();
        }
    }

    private function recordEvent(EventInterface $event): void
    {
        $this->uncommittedEvents[] = $event;
    }
}

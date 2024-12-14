<?php

namespace App\UserManagement\Domain\Events;

use App\SharedContext\Domain\Ports\Inbound\EventInterface;

final class UserCreatedEvent implements EventInterface
{
    private string $aggregateId;
    private string $email;
    private string $password;
    private string $firstname;
    private string $lastname;
    private bool $isConsentGiven;

    private array $roles;
    private \DateTimeImmutable $occurredOn;

    public function __construct(
        string $aggregateId,
        string $email,
        string $password,
        string $firstname,
        string $lastname,
        bool $isConsentGiven,
        array $roles,
    ) {
        $this->aggregateId = $aggregateId;
        $this->email = $email;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->isConsentGiven = $isConsentGiven;
        $this->roles = $roles;
        $this->occurredOn = new \DateTimeImmutable();
    }

    #[\Override]
    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function isConsentGiven(): bool
    {
        return $this->isConsentGiven;
    }

    #[\Override]
    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'aggregateId' => $this->aggregateId,
            'email' => $this->email,
            'password' => $this->password,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'isConsentGiven' => $this->isConsentGiven,
            'roles' => $this->roles,
            'occurredOn' => $this->occurredOn->format(\DateTimeInterface::ATOM),
        ];
    }

    #[\Override]
    public static function fromArray(array $data): self
    {
        $event = new self(
            $data['aggregateId'],
            $data['email'],
            $data['password'],
            $data['firstname'],
            $data['lastname'],
            $data['isConsentGiven'],
            $data['roles'],
        );
        $event->occurredOn = new \DateTimeImmutable($data['occurredOn']);

        return $event;
    }
}

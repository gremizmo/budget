<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Entity;

use App\Domain\Envelope\Entity\EnvelopeCollection;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Infra\Http\Rest\User\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserInstantiation(): void
    {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);
    }

    public function testGetSetId(): void
    {
        $user = new User();
        $user->setId(1);

        $this->assertEquals(1, $user->getId());
    }

    public function testGetSetEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testGetSetPassword(): void
    {
        $user = new User();
        $user->setPassword('password123');

        $this->assertEquals('password123', $user->getPassword());
    }

    public function testGetSetFirstname(): void
    {
        $user = new User();
        $user->setFirstname('John');

        $this->assertEquals('John', $user->getFirstname());
    }

    public function testGetSetLastname(): void
    {
        $user = new User();
        $user->setLastname('Doe');

        $this->assertEquals('Doe', $user->getLastname());
    }

    public function testIsSetConsentGiven(): void
    {
        $user = new User();
        $user->setConsentGiven(true);

        $this->assertTrue($user->isConsentGiven());
    }

    public function testGetSetConsentDate(): void
    {
        $user = new User();
        $consentDate = new \DateTimeImmutable('2023-01-01');
        $user->setConsentDate($consentDate);

        $this->assertEquals($consentDate, $user->getConsentDate());
    }

    public function testGetSetCreatedAt(): void
    {
        $user = new User();
        $createdAt = new \DateTimeImmutable('2023-01-01');
        $user->setCreatedAt($createdAt);

        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    public function testGetSetUpdatedAt(): void
    {
        $user = new User();
        $updatedAt = new \DateTime('2023-01-01');
        $user->setUpdatedAt($updatedAt);

        $this->assertEquals($updatedAt, $user->getUpdatedAt());
    }

    public function testGetSetRoles(): void
    {
        $user = new User();
        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);

        $this->assertEquals($roles, $user->getRoles());
    }

    public function testGetSetEnvelopes(): void
    {
        $user = new User();
        $envelopes = $this->createMock(EnvelopeCollection::class);
        $user->setEnvelopes($envelopes);

        $this->assertEquals($envelopes, $user->getEnvelopes());
    }

    public function testAddEnvelope(): void
    {
        $user = new User();
        $envelope = $this->createMock(EnvelopeInterface::class);
        $envelopes = $this->createMock(EnvelopeCollection::class);
        $envelopes->expects($this->once())->method('contains')->with($envelope)->willReturn(false);
        $envelopes->expects($this->once())->method('add')->with($envelope);
        $user->setEnvelopes($envelopes);

        $user->addEnvelope($envelope);
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        $user->eraseCredentials();

        $this->assertTrue(true); // No exception should be thrown
    }

    public function testGetUserIdentifier(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $this->assertEquals('test@example.com', $user->getUserIdentifier());
    }

    public function testGetSetPasswordResetToken(): void
    {
        $user = new User();
        $token = 'reset-token';
        $user->setPasswordResetToken($token);

        $this->assertSame($token, $user->getPasswordResetToken());
    }

    public function testGetSetPasswordResetTokenExpiry(): void
    {
        $user = new User();
        $expiryDate = new \DateTimeImmutable('2023-01-01');
        $user->setPasswordResetTokenExpiry($expiryDate);

        $this->assertSame($expiryDate, $user->getPasswordResetTokenExpiry());
    }
}

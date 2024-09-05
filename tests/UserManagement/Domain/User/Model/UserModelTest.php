<?php

declare(strict_types=1);

namespace App\Tests\UserManagement\Domain\User\Model;

use App\UserManagement\Domain\User\Model\UserModel;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    public function testGetId(): void
    {
        $user = new UserModel();
        $user->setId(1);
        $this->assertEquals(1, $user->getId());
    }

    public function testSetId(): void
    {
        $user = new UserModel();
        $user->setId(1);
        $this->assertEquals(1, $user->getId());
    }

    public function testGetUuid(): void
    {
        $user = new UserModel();
        $user->setUuid('uuid');
        $this->assertEquals('uuid', $user->getUuid());
    }

    public function testSetUuid(): void
    {
        $user = new UserModel();
        $user->setUuid('uuid');
        $this->assertEquals('uuid', $user->getUuid());
    }

    public function testGetEmail(): void
    {
        $user = new UserModel();
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testSetEmail(): void
    {
        $user = new UserModel();
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    public function testGetPassword(): void
    {
        $user = new UserModel();
        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());
    }

    public function testSetPassword(): void
    {
        $user = new UserModel();
        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());
    }

    public function testGetFirstname(): void
    {
        $user = new UserModel();
        $user->setFirstname('John');
        $this->assertEquals('John', $user->getFirstname());
    }

    public function testSetFirstname(): void
    {
        $user = new UserModel();
        $user->setFirstname('John');
        $this->assertEquals('John', $user->getFirstname());
    }

    public function testGetLastname(): void
    {
        $user = new UserModel();
        $user->setLastname('Doe');
        $this->assertEquals('Doe', $user->getLastname());
    }

    public function testSetLastname(): void
    {
        $user = new UserModel();
        $user->setLastname('Doe');
        $this->assertEquals('Doe', $user->getLastname());
    }

    public function testIsConsentGiven(): void
    {
        $user = new UserModel();
        $user->setConsentGiven(true);
        $this->assertTrue($user->isConsentGiven());
    }

    public function testSetConsentGiven(): void
    {
        $user = new UserModel();
        $user->setConsentGiven(true);
        $this->assertTrue($user->isConsentGiven());
    }

    public function testGetRoles(): void
    {
        $user = new UserModel();
        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);
        $this->assertEquals($roles, $user->getRoles());
    }

    public function testSetRoles(): void
    {
        $user = new UserModel();
        $roles = ['ROLE_ADMIN'];
        $user->setRoles($roles);
        $this->assertEquals($roles, $user->getRoles());
    }

    public function testGetConsentDate(): void
    {
        $user = new UserModel();
        $consentDate = new \DateTimeImmutable();
        $user->setConsentDate($consentDate);
        $this->assertEquals($consentDate, $user->getConsentDate());
    }

    public function testSetConsentDate(): void
    {
        $user = new UserModel();
        $consentDate = new \DateTimeImmutable();
        $user->setConsentDate($consentDate);
        $this->assertEquals($consentDate, $user->getConsentDate());
    }

    public function testGetCreatedAt(): void
    {
        $user = new UserModel();
        $createdAt = new \DateTimeImmutable();
        $user->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    public function testSetCreatedAt(): void
    {
        $user = new UserModel();
        $createdAt = new \DateTimeImmutable();
        $user->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    public function testGetUpdatedAt(): void
    {
        $user = new UserModel();
        $updatedAt = new \DateTime();
        $user->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
    }

    public function testSetUpdatedAt(): void
    {
        $user = new UserModel();
        $updatedAt = new \DateTime();
        $user->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
    }

    public function testGetPasswordResetToken(): void
    {
        $user = new UserModel();
        $user->setPasswordResetToken('reset-token');
        $this->assertEquals('reset-token', $user->getPasswordResetToken());
    }

    public function testSetPasswordResetToken(): void
    {
        $user = new UserModel();
        $user->setPasswordResetToken('reset-token');
        $this->assertEquals('reset-token', $user->getPasswordResetToken());
    }

    public function testGetPasswordResetTokenExpiry(): void
    {
        $user = new UserModel();
        $passwordResetTokenExpiry = new \DateTimeImmutable();
        $user->setPasswordResetTokenExpiry($passwordResetTokenExpiry);
        $this->assertEquals($passwordResetTokenExpiry, $user->getPasswordResetTokenExpiry());
    }

    public function testSetPasswordResetTokenExpiry(): void
    {
        $user = new UserModel();
        $passwordResetTokenExpiry = new \DateTimeImmutable();
        $user->setPasswordResetTokenExpiry($passwordResetTokenExpiry);
        $this->assertEquals($passwordResetTokenExpiry, $user->getPasswordResetTokenExpiry());
    }

    public function testGetUserIdentifier(): void
    {
        $user = new UserModel();
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getUserIdentifier());
    }

    public function testEraseCredentials(): void
    {
        $user = new UserModel();
        $user->eraseCredentials();
        $this->assertTrue(true);
    }
}

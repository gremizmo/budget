<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Factory;

use App\Domain\User\Dto\EditUserDto;
use App\Domain\User\Entity\User;
use App\Domain\User\Factory\EditUserFactory;
use PHPUnit\Framework\TestCase;

class EditUserFactoryTest extends TestCase
{
    public function testUpdateUser(): void
    {
        $editUserDto = new EditUserDto(
            email: 'updated@example.com',
            password: 'newpassword123',
            firstname: 'Jane',
            lastname: 'Smith',
            consentGiven: true,
        );

        $user = new User();
        $user->setFirstname('John')
            ->setLastname('Doe')
            ->setEmail('test@example.com')
            ->setPassword('password123');

        $factory = new EditUserFactory();
        $updatedUser = $factory->updateUser($user, $editUserDto);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals('Jane', $updatedUser->getFirstname());
        $this->assertEquals('Smith', $updatedUser->getLastname());
        $this->assertEquals('updated@example.com', $updatedUser->getEmail());
        $this->assertEquals('newpassword123', $updatedUser->getPassword());
        $this->assertInstanceOf(\DateTime::class, $updatedUser->getUpdatedAt());
    }
}

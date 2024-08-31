<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Factory;

use App\UserManagement\Application\User\Dto\EditUserInput;
use App\UserManagement\Domain\User\Factory\EditUserFactory;
use App\UserManagement\Infrastructure\User\Entity\User;
use PHPUnit\Framework\TestCase;

class EditUserFactoryTest extends TestCase
{
    public function testUpdateUser(): void
    {
        $editUserDto = new EditUserInput(
            firstname: 'Jane',
            lastname: 'Smith',
        );

        $user = new User();
        $user->setFirstname('John')
            ->setLastname('Doe');

        $factory = new EditUserFactory();
        $updatedUser = $factory->createFromDto($user, $editUserDto);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals('Jane', $updatedUser->getFirstname());
        $this->assertEquals('Smith', $updatedUser->getLastname());
        $this->assertInstanceOf(\DateTime::class, $updatedUser->getUpdatedAt());
    }
}

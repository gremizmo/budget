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

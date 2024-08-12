<?php

declare(strict_types=1);

namespace App\Tests\Application\Envelope\Query;

use App\Application\Envelope\Query\ListEnvelopesQuery;
use App\Domain\User\Entity\User;
use PHPUnit\Framework\TestCase;

class ListEnvelopesQueryTest extends TestCase
{
    public function testConstructorAndGetter(): void
    {
        $user = new User();
        $user->setId(1);
        $query = new ListEnvelopesQuery($user);

        $this->assertSame(1, $query->getUser()->getId());
    }
}

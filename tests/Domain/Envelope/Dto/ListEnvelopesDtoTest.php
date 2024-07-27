<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Dto;

use App\Domain\Envelope\Dto\ListEnvelopesDto;
use PHPUnit\Framework\TestCase;

class ListEnvelopesDtoTest extends TestCase
{
    public function testGetId(): void
    {
        $id = 1;
        $listEnvelopesDto = new ListEnvelopesDto($id);

        $this->assertSame($id, $listEnvelopesDto->getId());
    }

    public function testGetIdWithNull(): void
    {
        $listEnvelopesDto = new ListEnvelopesDto();

        $this->assertNull($listEnvelopesDto->getId());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Domain\Envelope\Dto;

use App\Domain\Envelope\Dto\CreateEnvelopeDto;
use PHPUnit\Framework\TestCase;

class CreateEnvelopeDtoTest extends TestCase
{
    public function testGetTitle(): void
    {
        $title = 'Test Title';
        $createEnvelopeDto = new CreateEnvelopeDto($title, '1000.00', '2000.00');

        $this->assertSame($title, $createEnvelopeDto->getTitle());
    }

    public function testGetCurrentBudget(): void
    {
        $currentBudget = '1000.00';
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', $currentBudget, '2000.00');

        $this->assertSame($currentBudget, $createEnvelopeDto->getCurrentBudget());
    }

    public function testGetTargetBudget(): void
    {
        $targetBudget = '2000.00';
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '1000.00', $targetBudget);

        $this->assertSame($targetBudget, $createEnvelopeDto->getTargetBudget());
    }

    public function testGetParentId(): void
    {
        $parentId = 1;
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '1000.00', '2000.00', $parentId);

        $this->assertSame($parentId, $createEnvelopeDto->getParentId());
    }

    public function testGetParentIdWithNull(): void
    {
        $createEnvelopeDto = new CreateEnvelopeDto('Test Title', '1000.00', '2000.00');

        $this->assertNull($createEnvelopeDto->getParentId());
    }
}

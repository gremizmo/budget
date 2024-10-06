<?php

declare(strict_types=1);

namespace App\Tests\EnvelopeManagement\Domain\Envelope\Model;

use App\EnvelopeManagement\Domain\Envelope\Exception\CurrentBudgetException;
use App\EnvelopeManagement\Domain\Envelope\Exception\TargetBudgetException;
use App\EnvelopeManagement\Domain\Envelope\Model\EnvelopeModel;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class EnvelopeModelTest extends TestCase
{
    public function testGetId(): void
    {
        $envelope = new EnvelopeModel();
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('id');
        $property->setValue($envelope, 1);
        $this->assertEquals(1, $envelope->getId());
    }

    public function testGetUuid(): void
    {
        $envelope = new EnvelopeModel();
        $envelope->setUuid('uuid');
        $this->assertEquals('uuid', $envelope->getUuid());
    }

    public function testSetUuid(): void
    {
        $envelope = new EnvelopeModel();
        $envelope->setUuid('uuid');
        $this->assertEquals('uuid', $envelope->getUuid());
    }

    public function testGetUpdatedAt(): void
    {
        $envelope = new EnvelopeModel();
        $updatedAt = new \DateTime();
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('updatedAt');
        $property->setValue($envelope, $updatedAt);
        $this->assertEquals($updatedAt, $envelope->getUpdatedAt());
    }

    public function testSetUpdatedAt(): void
    {
        $envelope = new EnvelopeModel();
        $updatedAt = new \DateTime();
        $envelope->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $envelope->getUpdatedAt());
    }

    public function testGetCurrentBudget(): void
    {
        $envelope = new EnvelopeModel();
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('currentBudget');
        $property->setValue($envelope, '100.00');
        $this->assertEquals('100.00', $envelope->getCurrentBudget());
    }

    public function testSetCurrentBudget(): void
    {
        $envelope = new EnvelopeModel();
        $envelope->setCurrentBudget('100.00');
        $this->assertEquals('100.00', $envelope->getCurrentBudget());
    }

    public function testGetTargetBudget(): void
    {
        $envelope = new EnvelopeModel();
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('targetBudget');
        $property->setValue($envelope, '200.00');
        $this->assertEquals('200.00', $envelope->getTargetBudget());
    }

    public function testSetTargetBudget(): void
    {
        $envelope = new EnvelopeModel();
        $envelope->setTargetBudget('200.00');
        $this->assertEquals('200.00', $envelope->getTargetBudget());
    }

    public function testGetParent(): void
    {
        $envelope = new EnvelopeModel();
        $parent = $this->createMock(EnvelopeModel::class);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('parent');
        $property->setValue($envelope, $parent);
        $this->assertEquals($parent, $envelope->getParent());
    }

    public function testSetParent(): void
    {
        $envelope = new EnvelopeModel();
        $parent = $this->createMock(EnvelopeModel::class);
        $envelope->setParent($parent);
        $this->assertEquals($parent, $envelope->getParent());
    }

    public function testGetChildren(): void
    {
        $envelope = new EnvelopeModel();
        $children = $this->createMock(ArrayCollection::class);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('children');
        $property->setValue($envelope, $children);
        $this->assertEquals($children, $envelope->getChildren());
    }

    public function testSetChildren(): void
    {
        $envelope = new EnvelopeModel();
        $children = $this->createMock(ArrayCollection::class);
        $envelope->setChildren($children);
        $this->assertEquals($children, $envelope->getChildren());
    }

    public function testGetUserUuid(): void
    {
        $envelope = new EnvelopeModel();
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('userUuid');
        $property->setValue($envelope, 'user-uuid');
        $this->assertEquals('user-uuid', $envelope->getUserUuid());
    }

    public function testSetUserUuid(): void
    {
        $envelope = new EnvelopeModel();
        $envelope->setUserUuid('user-uuid');
        $this->assertEquals('user-uuid', $envelope->getUserUuid());
    }

    public function testAddChild(): void
    {
        $envelope = new EnvelopeModel();
        $child = $this->createMock(EnvelopeModel::class);
        $children = $this->createMock(ArrayCollection::class);
        $children->expects($this->once())->method('add')->with($child);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('children');
        $property->setValue($envelope, $children);
        $envelope->addChild($child);
    }

    public function testCalculateChildrenCurrentBudgetOfParentEnvelope(): void
    {
        $envelope = new EnvelopeModel();
        $envelopeToUpdate = new EnvelopeModel();
        $envelopeToUpdate->setUuid('uuid');
        $child1 = $this->createMock(EnvelopeModel::class);
        $child1->method('getUuid')->willReturn('uuid1');
        $child1->method('getCurrentBudget')->willReturn('50.00');
        $child2 = $this->createMock(EnvelopeModel::class);
        $child2->method('getUuid')->willReturn('uuid2');
        $child2->method('getCurrentBudget')->willReturn('30.00');
        $children = $this->createMock(ArrayCollection::class);
        $children->method('toArray')->willReturn([$child1, $child2]);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('children');
        $property->setValue($envelope, $children);
        $this->assertEquals(80.00, $envelope->calculateChildrenCurrentBudgetOfParentEnvelope($envelopeToUpdate));
    }

    public function testValidateTargetBudgetIsLessThanParentTargetBudget(): void
    {
        $this->expectException(TargetBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setTargetBudget('100.00');
        $envelope->setCurrentBudget('50.00');
        $child = $this->createMock(EnvelopeModel::class);
        $child->method('getTargetBudget')->willReturn('60.00');
        $children = $this->createMock(ArrayCollection::class);
        $children->method('toArray')->willReturn([$child]);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('children');
        $property->setValue($envelope, $children);
        $envelope->validateTargetBudgetIsLessThanParentTargetBudget(200.00);
    }

    public function testValidateTargetBudgetIsLessThanParentAvailableTargetBudget(): void
    {
        $this->expectException(TargetBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setTargetBudget('100.00');
        $envelope->setCurrentBudget('50.00');
        $envelope->validateTargetBudgetIsLessThanParentAvailableTargetBudget(80.00, 20.00);
    }

    public function testValidateChildrenCurrentBudgetIsLessThanTargetBudget(): void
    {
        $this->expectException(CurrentBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setTargetBudget('100.00');
        $envelope->validateChildrenCurrentBudgetIsLessThanTargetBudget(120.00);
    }

    public function testValidateParentEnvelopeChildrenTargetBudgetIsLessThanTargetBudgetInput(): void
    {
        $this->expectException(TargetBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setTargetBudget('100.00');
        $child = $this->createMock(EnvelopeModel::class);
        $child->method('getTargetBudget')->willReturn('60.00');
        $children = $this->createMock(ArrayCollection::class);
        $children->method('toArray')->willReturn([$child]);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('children');
        $property->setValue($envelope, $children);
        $envelope->validateParentEnvelopeChildrenTargetBudgetIsLessThanTargetBudgetInput(50.00);
    }

    public function testValidateEnvelopeChildrenTargetBudgetIsLessThanTargetBudget(): void
    {
        $this->expectException(TargetBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setTargetBudget('100.00');
        $child = $this->createMock(EnvelopeModel::class);
        $child->method('getTargetBudget')->willReturn('60.00');
        $children = $this->createMock(ArrayCollection::class);
        $children->method('toArray')->willReturn([$child]);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('children');
        $property->setValue($envelope, $children);
        $envelope->validateEnvelopeChildrenTargetBudgetIsLessThanTargetBudget(50.00);
    }

    public function testValidateTargetBudgetIsLessThanParentMaxAllowableBudget(): void
    {
        $this->expectException(TargetBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setUuid('uuid');
        $envelope->setTitle('title');
        $envelope->setCreatedAt(new \DateTimeImmutable());
        $envelope->setTargetBudget('100.00');
        $envelope->setCurrentBudget('50.00');
        $child = $this->createMock(EnvelopeModel::class);
        $child->method('getTargetBudget')->willReturn('60.00');
        $children = $this->createMock(ArrayCollection::class);
        $children->method('toArray')->willReturn([$child]);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('children');
        $property->setValue($envelope, $children);
        $envelope->validateTargetBudgetIsLessThanParentMaxAllowableBudget($child, 200.00);
    }

    public function testValidateCurrentBudgetIsLessThanTargetBudget(): void
    {
        $this->expectException(CurrentBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->validateCurrentBudgetIsLessThanTargetBudget(120.00, 100.00);
    }

    public function testValidateCurrentBudgetIsLessThanParentTargetBudget(): void
    {
        $this->expectException(CurrentBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setTargetBudget('100.00');
        $envelope->validateCurrentBudgetIsLessThanParentTargetBudget(120.00);
    }

    public function testValidateChildrenCurrentBudgetIsLessThanCurrentBudget(): void
    {
        $this->expectException(CurrentBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setCurrentBudget('100.00');
        $child = $this->createMock(EnvelopeModel::class);
        $child->method('getCurrentBudget')->willReturn('60.00');
        $children = $this->createMock(ArrayCollection::class);
        $children->method('toArray')->willReturn([$child]);
        $reflection = new \ReflectionClass($envelope);
        $property = $reflection->getProperty('children');
        $property->setValue($envelope, $children);
        $envelope->validateChildrenCurrentBudgetIsLessThanCurrentBudget(50.00);
    }

    public function testUpdateAncestorsCurrentBudget(): void
    {
        $this->expectException(CurrentBudgetException::class);
        $envelope = new EnvelopeModel();
        $envelope->setCurrentBudget('100.00');
        $envelope->setTargetBudget('150.00');
        $envelope->updateAncestorsCurrentBudget(60.00);
    }
}

<?php

namespace App\Tests\Domain\Envelope\Validator;

use App\Application\Envelope\Query\GetEnvelopeByTitleQuery;
use App\Domain\Envelope\Exception\EnvelopeTitleAlreadyExistsForUserException;
use App\Domain\Envelope\Model\EnvelopeInterface;
use App\Domain\Envelope\Validator\EditEnvelopeTitleValidator;
use App\Domain\Shared\Adapter\QueryBusInterface;
use App\Domain\Shared\Model\UserInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TitleValidatorTest extends TestCase
{
    private QueryBusInterface&MockObject $queryBus;
    private EditEnvelopeTitleValidator $titleValidator;

    protected function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->titleValidator = new EditEnvelopeTitleValidator($this->queryBus);
    }

    public function testValidateThrowsExceptionWhenTitleAlreadyExists(): void
    {
        $title = 'Existing Title';
        $user = $this->createMock(UserInterface::class);
        $existingEnvelope = $this->createMock(EnvelopeInterface::class);
        $existingEnvelope->method('getId')->willReturn(1);

        $this->queryBus->method('query')
            ->with(new GetEnvelopeByTitleQuery($title, $user))
            ->willReturn($existingEnvelope);

        $this->expectException(EnvelopeTitleAlreadyExistsForUserException::class);

        $this->titleValidator->validate($title, $user);
    }

    /**
     * @throws EnvelopeTitleAlreadyExistsForUserException
     */
    public function testValidateDoesNotThrowExceptionWhenTitleDoesNotExist(): void
    {
        $title = 'New Title';
        $user = $this->createMock(UserInterface::class);

        $this->queryBus->method('query')
            ->with(new GetEnvelopeByTitleQuery($title, $user))
            ->willReturn(null);

        $this->titleValidator->validate($title, $user);

        $this->assertTrue(true);
    }

    public function testValidateUsersDontExist(): void
    {
        $title = 'Existing Title';
        $existingEnvelope = $this->createMock(EnvelopeInterface::class);
        $existingEnvelope->method('getId')->willReturn(1);
        $this->assertTrue(true);

        $this->titleValidator->validate($title);
    }
}

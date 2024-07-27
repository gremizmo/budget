<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Validator\Constraints;

use App\Application\Envelope\Query\GetOneEnvelopeQuery;
use App\Domain\Envelope\Dto\CreateEnvelopeDtoInterface;
use App\Domain\Envelope\Dto\UpdateEnvelopeDtoInterface;
use App\Domain\Shared\Adapter\MessengerQueryBusInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExceedsParentTargetBudgetValidator extends ConstraintValidator
{
    public function __construct(
        private readonly MessengerQueryBusInterface $messengerQueryBus,
    ) {
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ExceedsParentTargetBudget) {
            throw new UnexpectedTypeException($constraint, ExceedsParentTargetBudget::class);
        }

        if (!$value instanceof CreateEnvelopeDtoInterface && !$value instanceof UpdateEnvelopeDtoInterface) {
            return;
        }

        $parentEnvelope = $value->getParentId() ? $this->messengerQueryBus->query(
            new GetOneEnvelopeQuery($value->getParentId()),
        ) : null;

        if ($parentEnvelope && $parentEnvelope->exceedsTargetBudget(floatval($value->getTargetBudget()))) {
            $this->context->buildViolation($constraint->message)
                ->atPath('targetBudget')
                ->addViolation();
        }
    }
}

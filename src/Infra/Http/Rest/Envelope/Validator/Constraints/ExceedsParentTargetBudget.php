<?php

declare(strict_types=1);

namespace App\Infra\Http\Rest\Envelope\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ExceedsParentTargetBudget extends Constraint
{
    public string $message = 'The target budget of the child envelope exceeds the parent\'s target budget.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

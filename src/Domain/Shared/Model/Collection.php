<?php

declare(strict_types=1);

namespace App\Domain\Shared\Model;

class Collection extends \ArrayObject
{
    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function removeElement($element): bool
    {
        $key = array_search($element, $this->elements, true);

        if (false === $key) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    public function add($value): bool
    {
        $this->elements[] = $value;

        return true;
    }
}

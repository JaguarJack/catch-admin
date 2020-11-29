<?php
declare(strict_types=1);

namespace catcher\validates;

class Sometimes implements ValidateInterface
{

    public function type(): string
    {
        // TODO: Implement type() method.
        return 'sometimes';
    }

    public function verify($value): bool
    {
        // TODO: Implement verify() method.
        if ($value) {
            return true;
        }

        return false;
    }

    public function message(): string
    {
        // TODO: Implement message() method.
        return '';
    }
}

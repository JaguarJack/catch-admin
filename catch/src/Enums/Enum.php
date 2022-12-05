<?php

namespace Catch\Enums;

interface Enum
{
    public function value(): int;

    public function name(): string;
}

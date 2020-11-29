<?php
declare(strict_types=1);

namespace catcher\validates;

interface  ValidateInterface
{
    public function type(): string ;

    public function verify($value): bool ;

    public function message(): string ;
}

<?php
namespace catcher\validates;

interface  ValidateInterface
{
    public function type(): string ;

    public function verify($value, $field): bool ;

    public function message(): string ;
}

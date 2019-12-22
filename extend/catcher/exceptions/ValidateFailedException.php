<?php
namespace catcher\exceptions;

use catcher\Code;

class ValidateFailedException extends CatchException
{
    protected $code = Code::VALIDATE_FAILED;
}

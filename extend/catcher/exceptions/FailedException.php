<?php
declare(strict_types=1);

namespace catcher\exceptions;

use catcher\Code;

class FailedException extends CatchException
{
    protected $code = Code::FAILED;
}

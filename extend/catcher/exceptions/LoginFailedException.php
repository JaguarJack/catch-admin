<?php
declare(strict_types=1);

namespace catcher\exceptions;

use catcher\Code;

class LoginFailedException extends CatchException
{
    protected $code = Code::LOGIN_FAILED;

    protected $message = 'Login Failed! Please check you email or password';
}

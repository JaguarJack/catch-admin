<?php
namespace catcher\exceptions;

use catcher\Code;

class LoginFailedException extends CatchException
{
    protected $code = Code::LOGIN_FAILED;
}
<?php
namespace catcher\exceptions;

class PermissionForbiddenException extends CatchException
{
    protected $code = 10005;

    protected $message = 'permission forbidden';
}

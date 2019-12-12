<?php
namespace catcher\exceptions;

class PermissionForbiddenException extends \Exception
{
    protected $code = 10005;

    protected $message = 'permission forbidden';
}

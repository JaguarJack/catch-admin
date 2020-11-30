<?php
declare(strict_types=1);

namespace catcher\exceptions;

use catcher\Code;

class PermissionForbiddenException extends CatchException
{
    protected $code = Code::PERMISSION_FORBIDDEN;

    protected $message = 'permission forbidden';
}

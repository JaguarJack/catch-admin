<?php

namespace Modules\Permissions\Exceptions;

use Catch\Enums\Code;
use Catch\Exceptions\CatchException;

class PermissionForbidden extends CatchException
{
    protected $message = 'permission forbidden';

    protected $code = Code::PERMISSION_FORBIDDEN;
}

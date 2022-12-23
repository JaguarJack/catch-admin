<?php

namespace Modules\Permissions\Exceptions;

use Catch\Enums\Code;
use Catch\Exceptions\CatchException;
use Symfony\Component\HttpFoundation\Response;

class PermissionForbidden extends CatchException
{
    protected $message = 'permission forbidden';

    protected $code = Code::PERMISSION_FORBIDDEN;


    public function statusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}

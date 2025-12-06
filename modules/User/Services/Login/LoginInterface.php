<?php

namespace Modules\User\Services\Login;

use Modules\User\Models\User;

interface LoginInterface
{
    public function auth(array $params): ?User;
}

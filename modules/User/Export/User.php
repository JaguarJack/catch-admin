<?php

namespace Modules\User\Export;

use Catch\Support\Excel\Export;

class User extends Export
{
    protected array $header = [
        'id', '昵称', '邮箱', '创建时间',
    ];

    public function array(): array
    {
        // TODO: Implement array() method.
        return \Modules\User\Models\User::query()
            ->select('id', 'username', 'email', 'created_at')
            ->without('roles')
            ->get([
                'id', 'username', 'email', 'created_at',
            ])->toArray();
    }
}

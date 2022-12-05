<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace Catch\Traits\DB;

use Illuminate\Support\Facades\DB;

/**
 * transaction
 */
trait Trans
{
    /**
     * begin transaction
     */
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * commit
     */
    public function commit(): void
    {
        DB::commit();
    }

    /**
     * rollback
     */
    public function rollback(): void
    {
        DB::rollBack();
    }

    /**
     * transaction
     *
     * @param \Closure $closure
     */
    public function transaction(\Closure $closure): void
    {
        DB::transaction($closure);
    }
}

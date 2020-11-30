<?php
declare(strict_types=1);

namespace catcher\traits\db;

use think\facade\Db;

trait TransTrait
{
    /**
     *
     * @time 2019年12月03日
     * @return void
     */
    public function startTrans()
    {
        Db::startTrans();
    }

    /**
     *
     * @time 2019年12月03日
     * @return void
     */
    public function commit()
    {
        Db::commit();
    }

    /**
     *
     * @time 2019年12月03日
     * @return void
     */
    public function rollback()
    {
        Db::rollback();
    }

    /**
     *
     * @time 2019年12月03日
     * @param \Closure $function
     * @return void
     */
    public function transaction(\Closure $function)
    {
        Db::transaction($function());
    }
}

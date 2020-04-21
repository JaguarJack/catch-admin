<?php
namespace catchAdmin\index\controller;

use catcher\base\CatchController;
use think\facade\Db;

class Index extends CatchController
{
    /**
     *
     * @time 2019年12月12日
     * @throws \Exception
     * @return string
     */
    public function dashboard(): string
    {
        $mysqlVersion = Db::query('select version() as version');
        return $this->fetch([
            'mysql_version' => $mysqlVersion['0']['version'],
        ]);
    }
}

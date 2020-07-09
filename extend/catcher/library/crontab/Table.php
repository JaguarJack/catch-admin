<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\library\crontab;

use Swoole\Table as STable;

trait Table
{
    /**
     * @var STable
     */
    protected $table;

    protected function createTable()
    {
        $this->table = new STable(1024);

        $this->table->column('pid', STable::TYPE_INT, 4);       //1,2,4,8
        $this->table->column('memory', STable::TYPE_INT, 4);
        $this->table->column('start_at', STable::TYPE_INT, 8);
        $this->table->column('running_time', STable::TYPE_INT, 8);
        $this->table->column('status', STable::TYPE_STRING, 15);
        $this->table->column('deal_tasks', STable::TYPE_INT, 4);
        $this->table->column('errors', STable::TYPE_INT, 4);
        $this->table->create();

    }


    protected function addColumn($pid, $value)
    {
        return $this->table->set($pid, $value);
    }
}
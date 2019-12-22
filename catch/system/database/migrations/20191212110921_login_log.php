<?php

use think\migration\Migrator;
use think\migration\db\Column;

class LoginLog extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table  =  $this->table('login_log',['engine'=>'Myisam', 'comment' => '登录日志', 'signed' => false]);
        $table->addColumn('login_name', 'string',['limit'  =>  50,'default'=>'','comment'=>'用户名'])
              ->addColumn('login_ip', 'string',['default'=>0, 'limit' => 20, 'comment'=>'登录地点ip', 'signed' => false])
              ->addColumn('browser', 'string',['default'=> '','comment'=>'浏览器'])
              ->addColumn('os', 'string',['default'=> '','comment'=>'操作系统'])
              ->addColumn('login_at', 'integer', array('default'=>0,'comment'=>'登录时间', 'signed' => false ))
              ->addColumn('status', 'integer',['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,'default'=> 1,'comment'=>'1 成功 2 失败'])
              ->create();
    }
}

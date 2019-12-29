<?php

use think\migration\Migrator;
use think\migration\db\Column;

class OperateLog extends Migrator
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
        $table  =  $this->table('operate_log',['engine'=>'Myisam', 'comment' => '操作日志', 'signed' => false]);
        $table->addColumn('module', 'string',['limit'  =>  50,'default'=>'','comment'=>'模块名称'])
            ->addColumn('operate', 'string',['default'=> '', 'limit' => 20, 'comment'=>'操作模块'])
            ->addColumn('route', 'string',['default'=> '','limit' => 100, 'comment'=>'路由'])
            ->addColumn('params', 'string',['default'=> '','limit' => 1000, 'comment'=>'参数'])
            ->addColumn('ip', 'string',['default'=>'', 'limit' => 20,'comment'=>'ip', 'signed' => false])
            ->addColumn('creator_id', 'integer',['default'=> 0,'comment'=>'创建人ID', 'signed' => false])
            ->addColumn('method', 'string',['default'=> '','comment'=>'请求方法'])
            ->addColumn('created_at', 'integer', array('default'=>0,'comment'=>'登录时间', 'signed' => false ))
            ->create();
    }
}

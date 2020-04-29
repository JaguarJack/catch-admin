<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Roles extends Migrator
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
        $table  =  $this->table('roles',['engine'=>'Innodb', 'comment' => '角色表', 'signed' => false]);
        $table->addColumn('role_name', 'string',['limit'  =>  15,'default'=>'','comment'=>'角色名'])
            ->addColumn('parent_id', 'integer',['default'=>0,'comment'=>'父级ID', 'signed' => false])
            ->addColumn('description', 'string',['default'=> '','comment'=>'角色备注'])
            ->addColumn('data_range', 'integer',['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,'default'=> 0,'comment'=>'1 全部数据 2 自定义数据 3 仅本人数据 4 部门数据 5 部门及以下数据'])
            ->addColumn('creator_id', 'integer',['default' => 0, 'comment'=>'创建人ID'])
            ->addColumn('created_at', 'integer', array('default'=>0,'comment'=>'创建时间', 'signed' => false ))
            ->addColumn('updated_at', 'integer', array('default'=>0,'comment'=>'更新时间', 'signed' => false))
            ->addColumn('deleted_at', 'integer', array('default'=>0,'comment'=>'删除状态，0未删除 >0 已删除', 'signed' => false))
            ->create();
    }
}

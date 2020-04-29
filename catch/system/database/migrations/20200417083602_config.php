<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Config extends Migrator
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
        $table  =  $this->table('config',['engine'=>'InnoDB', 'comment' => '配置管理', 'signed' => false]);
        $table->addColumn('name', 'string',['limit'  =>  50,'default'=>'','comment'=>'配置名称'])
            ->addColumn('pid', 'integer', array('default'=> 0,'comment'=>'父级配置', 'signed' => false ))
            ->addColumn('component', 'string', ['default'=> '', 'limit' => 100, 'comment'=>'tab 引入的组件名称'])
            ->addColumn('key', 'string',['default'=> '', 'limit' => 100, 'comment'=>'配置键名'])
            ->addColumn('value', 'string',['default'=> '', 'limit' => 255, 'comment'=>'配置键值'])
            ->addColumn('status', 'integer',['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,'default'=> 1,'comment'=>'1 启用 2 禁用'])
            ->addColumn('creator_id', 'integer', array('default'=> 0,'comment'=>'创建人', 'signed' => false ))
            ->addColumn('created_at', 'integer', ['default'=> 0,'comment'=>'创建时间', 'signed' => false])
            ->addColumn('updated_at', 'integer', ['default'=> 0,'comment'=>'更新时间', 'signed' => false])
            ->addColumn('deleted_at', 'integer', ['default'=> 0,'comment'=>'删除时间', 'signed' => false])
            ->create();
    }
}

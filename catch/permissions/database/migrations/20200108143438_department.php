<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Department extends Migrator
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
      $table  =  $this->table('departments',['engine'=>'Innodb', 'comment' => '部门表', 'signed' => false]);
      $table->addColumn('department_name', 'string',['limit'  =>  15,'default'=>'','comment'=>'部门名称'])
        ->addColumn('parent_id', 'integer',['default'=>0,'comment'=>'父级ID', 'signed' => false])
        ->addColumn('principal', 'string', ['default' => '', 'comment' => '负责人', 'limit' => 20])
        ->addColumn('mobile', 'string', ['default' => '', 'comment' => '联系电话', 'limit' => 20])
        ->addColumn('email', 'string', ['default' => '', 'comment' => '联系又想', 'limit' => 100])
        ->addColumn('creator_id', 'integer',['default' => 0, 'comment'=>'创建人ID'])
        ->addColumn('status', 'integer',['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,'default'=> 1,'comment'=>'1 正常 2 停用'])
        ->addColumn('sort', 'integer',['default'=> 0,'comment'=>'排序字段'])
        ->addColumn('created_at', 'integer', array('default'=>0,'comment'=>'创建时间', 'signed' => false ))
        ->addColumn('updated_at', 'integer', array('default'=>0,'comment'=>'更新时间', 'signed' => false))
        ->addColumn('deleted_at', 'integer', array('default'=>0,'comment'=>'删除状态，null 未删除 timestamp 已删除', 'signed' => false))
        ->create();
    }
}

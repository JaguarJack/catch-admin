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
        $table->addColumn('name', 'string',['limit'  =>  15,'default'=>'','comment'=>'角色名'])
            ->addColumn('parent_id', 'integer',['default'=>0,'comment'=>'父级ID', 'signed' => false])
            ->addColumn('description', 'string',['default'=> '','comment'=>'角色备注'])
            ->addColumn('created_at', 'integer', array('default'=>0,'comment'=>'创建时间', 'signed' => false ))
            ->addColumn('updated_at', 'integer', array('default'=>0,'comment'=>'更新时间', 'signed' => false))
            ->addColumn('deleted_at', 'integer', array('null'=>true,'comment'=>'删除状态，0未删除 >0 已删除', 'signed' => false))
            ->create();
    }
}

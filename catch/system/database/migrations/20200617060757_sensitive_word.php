<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SensitiveWord extends Migrator
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
        $table  =  $this->table('sensitive_word',['engine'=>'InnoDB', 'comment' => '敏感词库', 'signed' => false]);
        $table->addColumn('word', 'string',['limit'  =>  50,'default'=>'','comment'=>'词汇'])
              ->addColumn('creator_id', 'integer', array('default'=>0, 'comment'=>'创建人ID', 'signed' => false ))
              ->addColumn('created_at', 'integer', array('default'=>0, 'comment'=>'创建时间', 'signed' => false ))
              ->addColumn('updated_at', 'integer', array('default'=>0, 'comment'=>'更新时间', 'signed' => false ))
              ->addColumn('deleted_at', 'integer', array('default'=>0, 'comment'=>'删除时间', 'signed' => false ))
              ->create();
    }
}

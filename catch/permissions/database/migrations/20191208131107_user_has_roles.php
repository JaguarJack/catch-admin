<?php

use think\migration\Migrator;
use think\migration\db\Column;

class UserHasRoles extends Migrator
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
        $table  =  $this->table('user_has_roles',['engine'=>'Innodb', 'comment' => '用户角色表', 'signed' => false]);
        $table->addColumn('uid', 'integer',['comment'=>'用户ID', 'signed' => false])
            ->addColumn('role_id', 'integer', ['comment'=>'角色ID', 'signed' => false])
            ->create();
    }
}

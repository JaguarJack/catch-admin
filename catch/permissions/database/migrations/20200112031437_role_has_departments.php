<?php

use think\migration\Migrator;
use think\migration\db\Column;

class RoleHasDepartments extends Migrator
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
        $table  =  $this->table('role_has_departments', ['engine'=>'Innodb', 'comment' => '角色部门表', 'signed' => false]);
        $table->addColumn('role_id', 'integer', ['comment'=>'角色ID', 'signed' => false])
            ->addColumn('department_id', 'integer', ['comment'=>'部门ID', 'signed' => false])
            ->create();
    }
}

<?php

use think\migration\Migrator;
use think\migration\db\Column;

class UpdateRoles extends Migrator
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
        if ($this->hasTable('roles')) {
            $table = $this->table('roles');

            $table->addColumn('identify', 'string', [
                            'limit' => 20,
                            'default' => 1,
                            'comment' => '角色的标识，用英文表示，用于后台路由权限',
                            'after' => 'role_name'])
                            ->update();
        }
    }
}

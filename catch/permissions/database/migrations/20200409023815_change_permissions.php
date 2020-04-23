<?php

use think\migration\Migrator;
use think\migration\db\Column;

class ChangePermissions extends Migrator
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
        if ($this->hasTable('permissions')) {
            $table = $this->table('permissions');

            $table->addColumn('component', 'string', ['default' => '', 'comment' => '组件名称', 'limit' => '255', 'after' => 'permission_mark'])
                ->addColumn('redirect', 'string', ['default' => '', 'comment' => '跳转地址', 'limit' => '255', 'after' => 'component'])
                ->addColumn('hide_children_in_menu', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '1 显示 2隐藏', 'after' => 'redirect'])
                ->addColumn('keepalive', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '1 缓存 2 不存在 ', 'after' => 'hide_children_in_menu'])
                ->update();
        }
    }
}

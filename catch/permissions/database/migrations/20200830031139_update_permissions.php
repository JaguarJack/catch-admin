<?php

use think\migration\Migrator;
use think\migration\db\Column;

class UpdatePermissions extends Migrator
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

            $table->renameColumn('method', 'hidden_children_in_menu')
                ->addColumn('breadcrumb', 'integer', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,
                'default' => 1,
                'comment' => '是否显示在面包屑 1 显示 2 隐藏',
                'after' => 'redirect'])
                ->addColumn('affix', 'integer', [
                    'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,
                    'default' => 2,
                    'comment' => '是否固定在 tag-view 1 固定 2 不固定',
                    'after' => 'breadcrumb'])
                ->update();
        }
    }
}

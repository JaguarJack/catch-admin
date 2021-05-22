<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~{$year} http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

use think\migration\Migrator;
use think\migration\db\Column;

class AddModelFields extends Migrator
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
        if ($this->hasTable('cms_models')) {
            $this->table('cms_models')
                ->addColumn('used_at_list', 'string', ['limit' => 512,'null' => false,'default' => '','signed' => true,'comment' => '用在列表的字段', 'after' => 'description'])
                ->addColumn('used_at_search', 'string', ['limit' => 512,'null' => false,'default' => '','signed' => true,'comment' => '用在搜索的字段', 'after' => 'description'])
                ->addColumn('used_at_detail', 'string', ['limit' => 512,'null' => false,'default' => '','signed' => true,'comment' => '用在详情的字段', 'after' => 'description'])
                ->update();
        }
    }
}

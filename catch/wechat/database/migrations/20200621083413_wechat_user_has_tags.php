<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class WechatUserHasTags extends Migrator
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
        $table = $this->table('wechat_user_has_tags', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '用户标签关联表' ,'signed' => true ]);
        $table->addColumn('tag_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => true,'signed' => false,'comment' => '微信 tagId',])
              ->addColumn('user_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '更新时间',])
              ->create();
    }
}

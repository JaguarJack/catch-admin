<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class WechatGraphic extends Migrator
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
        $table = $this->table('wechat_graphic', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '微信图文管理' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('title', 'string', ['limit' => 255, 'comment' => '标题',])            ->addColumn('author', 'string', ['default' => 'admin', 'limit' => 20,'signed' => true,'comment' => '作者'])
            ->addColumn('parent_id', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_REGULAR,'signed' => true,'comment' => '图文第一篇'])
            ->addColumn('cover', 'string', ['limit' => 255,'signed' => true,'comment' => '封面'])
            ->addColumn('content', 'text', ['comment' => '内容'])
            ->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建人ID',])
            ->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建时间',])
            ->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '更新时间',])
            ->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '软删除',])
            ->create();
    }
}

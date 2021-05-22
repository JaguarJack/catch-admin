<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsSiteLinks extends Migrator
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
        $table = $this->table('cms_site_links', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '友情链接' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('title', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '友情链接标题',])
			->addColumn('link_to', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '跳转地址',])
			->addColumn('weight', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => false,'default' => 1,'signed' => true,'comment' => '权重',])
			->addColumn('is_show', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '1 展示 2 隐藏',])
			->addColumn('icon', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '网站图标',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}

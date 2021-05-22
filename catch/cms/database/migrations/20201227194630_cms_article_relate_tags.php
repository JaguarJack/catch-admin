<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsArticleRelateTags extends Migrator
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
        $table = $this->table('cms_article_relate_tags', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '文章关联标签表' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('article_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => true,'signed' => false,'comment' => '文章ID',])
			->addColumn('tag_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => true,'signed' => false,'comment' => '标签ID',])
            ->create();
    }
}

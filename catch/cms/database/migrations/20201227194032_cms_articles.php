<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsArticles extends Migrator
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
        $table = $this->table('cms_articles', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '文章表' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('title', 'string', ['limit' => 255,'null' => true,'signed' => true,'comment' => '文章标题',])
			->addColumn('category_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => true,'signed' => true,'comment' => '分类ID',])
			->addColumn('images', 'string', ['limit' => 1000,'null' => false,'default' => '','signed' => true,'comment' => '多图集合',])
			->addColumn('tags', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '标签集合',])
			->addColumn('url', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '自定义URL',])
			->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => false,'signed' => true,'comment' => '内容',])
			->addColumn('keywords', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '关键字',])
			->addColumn('description', 'string', ['limit' => 1000,'null' => false,'default' => '','signed' => true,'comment' => '描述',])
			->addColumn('pv', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '浏览量',])
			->addColumn('likes', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '喜欢',])
			->addColumn('comments', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '评论数',])
			->addColumn('is_top', 'boolean', ['null' => false,'default' => 2,'signed' => true,'comment' => '1 置顶 2 非置顶',])
			->addColumn('is_recommend', 'boolean', ['null' => false,'default' => 2,'signed' => true,'comment' => '1 推荐 2 不推荐',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '1 展示 2 隐藏',])
			->addColumn('is_can_comment', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '1 允许 2 不允许',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}

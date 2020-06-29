<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class WechatReply extends Migrator
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
        $table = $this->table('wechat_reply', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '微信回复' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('keyword', 'string', ['limit' => 255, 'default' => '', 'comment' => '关键字',])
            ->addColumn('media_id', 'string', ['default' => '', 'limit' => 100,'signed' => true,'comment' => '微信资源ID'])
            ->addColumn('media_url', 'string', ['default' => '', 'limit' => 255,'signed' => true,'comment' => '本地资源 URL'])
            ->addColumn('image_url', 'string', ['default' => '', 'limit' => 255,'signed' => true,'comment' => '本地图片 URL'])
            ->addColumn('title', 'string', ['limit' => '255', 'default' => '', 'comment' => '标题'])
            ->addColumn('content', 'string', ['limit' => 1000, 'comment' => '内容', 'default' => '',])
            ->addColumn('type', 'integer', ['limit' => MysqlAdapter::INT_TINY,'null' => false,'default' => 1,'signed' => true,'comment' => '1文字 2图文 3图片 4音乐 5视频 6语音 7转客服',])
            ->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_TINY,'default' => 1,'signed' => true,'comment' => '1 正常 2 禁用',])
            ->addColumn('rule_type', 'integer', ['limit' => MysqlAdapter::INT_TINY,'null' => false,'default' => 1,'signed' => true,'comment' => '1 关键字 2 关注 3 默认',])
            ->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建人ID',])
            ->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建时间',])
            ->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '更新时间',])
            ->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '软删除',])
            ->create();
    }
}

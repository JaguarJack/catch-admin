<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class WechatMaterial extends Migrator
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
        $table = $this->table('wechat_material', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '微信素材' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('tag_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => true,'signed' => false,'comment' => '微信 tagId',])
            ->addColumn('name', 'string', ['limit' => 30,'null' => true,'comment' => '标签名称',])
            ->addColumn('fans_amount', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '粉丝数量',])
            ->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建时间',])
            ->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '更新时间',])
            ->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '软删除',])
            ->addIndex(['tag_id'], ['unique' => true,'name' => 'unique_tag_id'])
            ->create();
    }
}

<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class TaskMemberVip extends Migrator
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
        $table = $this->table('member_vip', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_0900_ai_ci', 'comment' => '会员等级表' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('level_code', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '等级代码',])
            ->addColumn('level_title', 'char', ['limit' => 16,'null' => false,'default' => 0,'signed' => true,'comment' => '等级名称',])
            ->addColumn('min_factor', 'decimal', ['precision' => 10,'scale' => 0,'null' => false,'default' => 0,'signed' => true,'comment' => '最低条件',])
            ->addColumn('max_factor', 'decimal', ['precision' => 10,'scale' => 0,'null' => false,'default' => 0,'signed' => true,'comment' => '最高条件',])
            ->addColumn('one_level_reward', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '一级奖励',])
            ->addColumn('tow_level_reward', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '二级奖励',])
            ->addColumn('three_level_reward', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '三级奖励',])
            ->addColumn('expire_day', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '有效期',])
            ->addColumn('valid_invite', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '有效推荐',])
            ->addColumn('reward_count', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '每日抢单数',])
            ->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
            ->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
            ->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
            ->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->addIndex(['level_code'], ['unique' => true,'name' => 'unique_level_code'])
            ->create();
    }
}

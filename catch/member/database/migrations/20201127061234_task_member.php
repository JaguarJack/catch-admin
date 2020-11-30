<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class TaskMember extends Migrator
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
        $table = $this->table('member', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_0900_ai_ci', 'comment' => '会员表流水' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('avatar', 'char', ['limit' => 10,'null' => true,'signed' => true,'comment' => '会员头像',])
            ->addColumn('balance_total', 'boolean', ['null' => true,'signed' => true,'comment' => '余额合计',])
            ->addColumn('diff_total', 'char', ['limit' => 32,'null' => false,'default' => 0,'signed' => true,'comment' => '冻结余额',])
            ->addColumn('invite_code', 'char', ['limit' => 8,'null' => false,'default' => 0,'signed' => true,'comment' => '邀请码',])
            ->addColumn('last_login_time', 'boolean', ['null' => true,'signed' => true,'comment' => '最后登录时间',])
            ->addColumn('password', 'char', ['limit' => 64,'null' => false,'default' => 0,'signed' => true,'comment' => '登录密码',])
            ->addColumn('password_safety', 'char', ['limit' => 32,'null' => false,'default' => 0,'signed' => true,'comment' => '登录密码安全码',])
            ->addColumn('mobile', 'char', ['limit' => 64,'null' => false,'default' => 0,'signed' => true,'comment' => '手机号',])
            ->addColumn('nickname', 'char', ['limit' => 32,'null' => true,'signed' => true,'comment' => '昵称',])
            ->addColumn('profile', 'char', ['limit' => 255,'null' => true,'signed' => true,'comment' => '个性签名',])
            ->addColumn('qq', 'boolean', ['null' => true,'signed' => true,'comment' => '会员QQ',])
            ->addColumn('register_ip', 'char', ['limit' => 32,'null' => false,'default' => 127,'signed' => true,'comment' => '注册IP',])
            ->addColumn('remark', 'char', ['limit' => 32,'null' => true,'signed' => true,'comment' => '备注',])
            ->addColumn('uuid', 'char', ['limit' => 32,'null' => false,'default' => 0,'signed' => true,'comment' => 'uuid',])
            ->addColumn('vip_expired_time', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '会员到期时间',])
            ->addColumn('vip_level_id', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '会员等级id',])
            ->addColumn('is_frozen', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '是否冻结 0否 1是',])
            ->addColumn('pay_password', 'char', ['limit' => 64,'null' => false,'default' => 0,'signed' => true,'comment' => '支付密码',])
            ->addColumn('pay_password_safety', 'char', ['limit' => 32,'null' => false,'default' => 0,'signed' => true,'comment' => '支付密码安全码',])
            ->addColumn('status', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '会员状态',])
            ->addColumn('is_inside', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '是否内部用户0否 1是',])
            ->addColumn('channel', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '渠道',])
            ->addColumn('real_name', 'char', ['limit' => 8,'null' => false,'default' => '','signed' => true,'comment' => '真实名字',])
            ->addColumn('id_card', 'char', ['limit' => 18,'null' => false,'default' => '','signed' => true,'comment' => '身份证号码',])
            ->addColumn('collect_id', 'boolean', ['null' => true,'signed' => true,'comment' => '收款资料ID',])
            ->addColumn('parent_id', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '推荐人ID',])
            ->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
            ->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
            ->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
            ->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->addIndex(['uuid'], ['unique' => true,'name' => 'unique_uuid'])
            ->create();
    }
}

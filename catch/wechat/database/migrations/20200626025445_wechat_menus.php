<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class WechatMenus extends Migrator
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
        $table = $this->table('wechat_menus', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '微信菜单' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('name', 'string', ['limit' => 30,'null' => true,'comment' => '菜单名称',])
            ->addColumn('parent_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL,'null' => false,'default' => 0,'signed' => true,'comment' => '父级ID',])
            ->addColumn('type', 'string', ['null' => false, 'limit' => 100, 'comment' => '类型',])
            ->addColumn('key', 'string', ['null' => false, 'limit' => 30, 'comment' => 'key',])
            ->addColumn('url', 'string', ['default' => '', 'limit' => 255, 'comment' => 'view 类型  url 链接',])
            ->addColumn('appid', 'string', ['default' => '', 'limit' => 100, 'comment' => '小程序 appid',])
            ->addColumn('pagepath', 'string', ['default' => '', 'limit' => 255, 'comment' => '小程序页面',])
            ->addColumn('media_id', 'string', ['default' => '', 'limit' => 100, 'comment' => '调用新增永久素材接口返回的合法media_id'])
            ->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '创建时间',])
            ->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '更新时间',])
            ->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '软删除',])
            ->create();
    }
}

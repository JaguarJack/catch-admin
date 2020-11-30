<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class TaskTradeGoods extends Migrator
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
        $table = $this->table('trade_goods', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_0900_ai_ci', 'comment' => '任务商品表' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('commission', 'decimal', ['precision' => 8,'scale' => 2,'null' => false,'default' => 0,'signed' => true,'comment' => '佣金|text|input|input',])
			->addColumn('exec_method', 'char', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '执行方法|text|input|input',])
			->addColumn('exec_param', 'json', ['null' => true,'signed' => true,'comment' => '执行参数|text|input|input',])
			->addColumn('fulfil_total', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '任务完成总量|text|input',])
			->addColumn('goods_brief', 'char', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '商品简介|text|input|input|input',])
			->addColumn('goods_detail', 'char', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '商品详情|text|input|input',])
			->addColumn('goods_img', 'char', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '商品图片|upload-image|image-uploader|image-uploader',])
			->addColumn('goods_title', 'char', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '商品标题|text|input|input|input',])
			->addColumn('min_vip_level', 'char', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '最低会员等级|text|select|select|select',])
			->addColumn('min_vip_title', 'char', ['limit' => 16,'null' => false,'default' => '','signed' => true,'comment' => '最低会员等级名称|text',])
			->addColumn('stock_total', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '库存总量|text|input|input|input',])
			->addColumn('task_code', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '任务代码|text|input|input|input',])
			->addColumn('task_title', 'char', ['limit' => 64,'null' => false,'default' => '','signed' => true,'comment' => '任务标题|text|input|input|input',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间|text|||date',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间|text|||date',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除',])
            ->create();
    }
}

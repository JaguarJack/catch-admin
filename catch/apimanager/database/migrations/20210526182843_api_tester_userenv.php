<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class ApiTesterUserenv extends Migrator
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
        $table = $this->table('api_tester_userenv', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => 'API测试用户环境' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('env_name', 'string', ['limit' => 128,'null' => false,'default' => '','signed' => true,'comment' => '环境名称',])
			->addColumn('appid', 'string', ['limit' => 64,'null' => false,'default' => '','signed' => true,'comment' => 'appid',])
			->addColumn('project_id', 'string', ['limit' => 64,'null' => false,'default' => '','signed' => true,'comment' => '项目ID',])
			->addColumn('env_json', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => false,'signed' => true,'comment' => '环境变量json',])
			->addColumn('selected', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '是否当前选中:0=否,1=是',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除字段',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
            ->create();
    }
}

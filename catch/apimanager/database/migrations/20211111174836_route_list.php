<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class RouteList extends Migrator
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
        $table = $this->table('route_list', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '路由表' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('rule', 'string', ['limit' => 128,'null' => true,'signed' => true,'comment' => 'rule',])
			->addColumn('route', 'string', ['limit' => 256,'null' => true,'signed' => true,'comment' => 'route',])
			->addColumn('method', 'string', ['limit' => 16,'null' => true,'signed' => true,'comment' => 'method',])
			->addColumn('name', 'string', ['limit' => 256,'null' => true,'signed' => true,'comment' => 'name',])
			->addColumn('domain', 'string', ['limit' => 128,'null' => true,'signed' => true,'comment' => 'domain',])
			->addColumn('option', 'string', ['limit' => 256,'null' => true,'signed' => true,'comment' => 'option',])
			->addColumn('pattern', 'string', ['limit' => 128,'null' => true,'signed' => true,'comment' => 'pattern',])
			->addColumn('title', 'string', ['limit' => 128,'null' => true,'signed' => true,'comment' => 'title',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除字段',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
			->addIndex(['name'], ['unique' => true,'name' => 'route_list_name'])
            ->create();
    }
}

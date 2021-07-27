<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class ApiTester extends Migrator
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
        $table = $this->table('api_tester', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => 'api测试表' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('api_title', 'string', ['limit' => 128,'null' => false,'default' => '新建接口','signed' => true,'comment' => '标题',])
			->addColumn('api_name', 'string', ['limit' => 128,'null' => false,'default' => '','signed' => true,'comment' => '英文唯一标识',])
			->addColumn('category_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '分类',])
			->addColumn('type', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '数据源类型:1=remote,2=local',])
			->addColumn('appid', 'string', ['limit' => 64,'null' => true,'signed' => true,'comment' => 'appid',])
			->addColumn('project_id', 'string', ['limit' => 64,'null' => true,'signed' => true,'comment' => '项目ID',])
			->addColumn('api_url', 'string', ['limit' => 512,'null' => false,'default' => 'https://127.0.0.1','signed' => true,'comment' => 'API URL',])
			->addColumn('methods', 'string', ['limit' => 128,'null' => false,'default' => 'POST','signed' => true,'comment' => '方法:POST,GET,PUT,PATCH,DELETE,COPY,HEAD,OPTIONS',])
			->addColumn('auth', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => '鉴权',])
			->addColumn('header', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => 'header',])
			->addColumn('query', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => 'query',])
			->addColumn('body', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => 'body',])
			->addColumn('doc_url', 'string', ['limit' => 512,'null' => true,'signed' => true,'comment' => '文档URL',])
			->addColumn('document', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => '文档',])
			->addColumn('sample_data', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => '示例数据',])
			->addColumn('sample_result', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => '示例返回数据',])
			->addColumn('sort', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => true,'signed' => true,'comment' => '排序',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 1,'signed' => true,'comment' => '状态:1=已完成,2=待开发,3=开发中,4=已废弃',])
			->addColumn('content_type', 'string', ['limit' => 128,'null' => false,'default' => 'application/x-www-form-urlencoded','signed' => true,'comment' => 'content-type:application/x-www-form-urlencoded,multipart/form-data,raw',])
			->addColumn('env_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => true,'signed' => true,'comment' => '环境ID',])
			->addColumn('memo', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => '备注',])
			->addColumn('created_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建时间',])
			->addColumn('updated_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '更新时间',])
			->addColumn('deleted_at', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '软删除字段',])
			->addColumn('creator_id', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => false,'comment' => '创建人ID',])
            ->create();
    }
}

<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class Rbac extends Migrator
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
		$this->up();
    }

    public function up()
	{
		$table = $this->table(config('permissions.table.role'), [ 'engine'=>'InnoDB']);
		$table->addColumn('name', 'string',['limit' => 50, 'default'=>'','comment'=>'角色名称'])
						->addColumn('created_at', 'timestamp', [ 'comment' => '创建时间'])
						->addColumn('updated_at', 'timestamp', [ 'comment' => '更新时间'])
						->addIndex(['name'], ['unique' => true])
						->create();

		$table = $this->table(config('permissions.table.permission'), ['engine' => 'InnoDB']);
		$table->addColumn('name', 'string',['limit' => 50, 'default'=>'','comment'=>'菜单名称'])
			  ->addColumn('icon', 'string', ['limit' => 50, 'default'=>'', 'comment'=>'菜单图标'])
			  ->addColumn('pid', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment'=>'父级菜单ID'])
			  ->addColumn('module', 'string',['limit' => 50, 'default'=>'','comment'=>'模块名称'])
			  ->addColumn('controller', 'string',['limit' => 50, 'default'=>'','comment'=>'控制器名称'])
			  ->addColumn('action', 'string',['limit' => 50, 'default'=>'1','comment'=>'方法名称'])
			  ->addColumn('is_show', 'integer',['limit' => MysqlAdapter::INT_TINY, 'default'=> 1,'comment'=>'1 展示 2 隐藏'])
			  ->addColumn('created_at', 'timestamp', [ 'comment' => '创建时间'])
			  ->addColumn('updated_at', 'timestamp', [ 'comment' => '更新时间'])
			  ->addIndex(['name'], ['unique' => true])
			  ->create();

		$table = $this->table(config('permissions.table.user_has_roles'), ['engine' => 'InnoDB', 'identity' => true]);
		$table->addColumn('uid', 'integer',['limit' => 11, 'comment'=>'用户ID'])
			  ->addColumn('role_id', 'integer', [ 'comment' => '角色ID'])
			  ->create();

		$table = $this->table(config('permissions.table.role_has_permissions'), ['engine' => 'InnoDB', 'identity' => true]);
		$table->addColumn('role_id', 'integer',['limit' => 11, 'comment'=>'角色ID'])
			  ->addColumn('permission_id', 'integer', [ 'comment' => '权限ID'])
			  ->create();
	}

	public function down()
	{

	}
}

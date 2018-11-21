<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Users extends Migrator
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
	    $table = $this->table('users', ['engine' => 'InnoDB']);
	    $table->addColumn('name', 'string',['limit' => 50, 'default'=>'','comment'=>'用户名'])
		    ->addColumn('email', 'string',['limit' => 255, 'default'=>'','comment'=>'邮箱'])
		    ->addColumn('password', 'string',['limit' => 255, 'default'=>'','comment'=>'密码'])
		    ->addColumn('remember_token', 'string',['limit' => 255, 'default'=>'','comment'=>'记住token'])
		    ->addColumn('login_ip', 'string',['limit' => 50, 'default'=>'','comment'=>'登录IP'])
		    ->addColumn('created_at', 'timestamp', [ 'comment' => '更新时间'])
		    ->addColumn('login_at', 'timestamp', [ 'comment' => '最近登录时间'])
		    ->addIndex(['name', 'email'], ['unique' => true])
		    ->create();
    }
}

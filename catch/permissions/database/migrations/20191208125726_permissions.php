<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Permissions extends Migrator
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
        $table  =  $this->table('permissions',['engine'=>'Innodb', 'comment' => '菜单表', 'signed' => false]);
        $table->addColumn('permission_name', 'string',['limit'  =>  15,'default'=>'','comment'=>'菜单名称'])
            ->addColumn('parent_id', 'integer',['default'=>0,'comment'=>'父级ID', 'signed' => false])
            ->addColumn('route', 'string', ['default' => '', 'comment' => '路由', 'limit' => 50])
            ->addColumn('icon', 'string', ['default' => '', 'comment' => '菜单图标', 'limit' => 50])
            ->addColumn('module', 'string', ['default' => '', 'comment' => '模块', 'limit' => 20])
            ->addColumn('creator_id', 'integer',['default' => 0, 'comment'=>'创建人ID'])
            ->addColumn('method', 'string', ['default' => 'get', 'comment' => '路由请求方法', 'limit' => 15])
            ->addColumn('permission_mark', 'string', ['null' => false, 'comment' => '权限标识', 'limit' => 50])
            ->addColumn('type', 'integer',['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,'default'=> 1,'comment'=>'1 菜单 2 按钮'])
            ->addColumn('sort', 'integer',['default'=> 0,'comment'=>'排序字段'])
            ->addColumn('created_at', 'integer', array('default'=>0,'comment'=>'创建时间', 'signed' => false ))
            ->addColumn('updated_at', 'integer', array('default'=>0,'comment'=>'更新时间', 'signed' => false))
            ->addColumn('deleted_at', 'integer', array('default'=>0,'comment'=>'删除状态，null 未删除 timestamp 已删除', 'signed' => false))
            ->create();

    }
}

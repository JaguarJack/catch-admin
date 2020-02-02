<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Attachments extends Migrator
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
        $table  =  $this->table('attachments',['engine'=>'Myisam', 'comment' => '附件管理', 'signed' => false]);
        $table->addColumn('path', 'string',['limit'  =>  50,'default'=>'','comment'=>'附件存储路径'])
              ->addColumn('url', 'string',['default'=> '', 'limit' => 100, 'comment'=>'资源地址'])
              ->addColumn('mime_type', 'string',['default'=> '', 'limit' => 100, 'comment'=>'资源mimeType'])
              ->addColumn('file_ext', 'string',['default'=> '','limit' => 100, 'comment'=>'资源后缀'])
              ->addColumn('file_size', 'integer',['default'=> 0, 'comment'=>'资源大小'])
              ->addColumn('filename', 'string',['default'=>'', 'limit' => 255, 'comment'=>'资源名称'])
              ->addColumn('driver', 'string',['default'=> 0, 'limit' => 20, 'comment' => 'local,oss,qcloud,qiniu'])
              ->addColumn('created_at', 'integer', array('default'=>0, 'comment'=>'创建时间', 'signed' => false ))
              ->addColumn('updated_at', 'integer', array('default'=>0, 'comment'=>'更新时间', 'signed' => false ))
              ->addColumn('deleted_at', 'integer', array('default'=>0, 'comment'=>'删除时间', 'signed' => false ))
              ->create();
    }
}

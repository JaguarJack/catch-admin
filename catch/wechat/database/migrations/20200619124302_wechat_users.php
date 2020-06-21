<?php

use think\migration\Migrator;
use think\migration\db\Column;

class WechatUsers extends Migrator
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
        $table  =  $this->table('wechat_users',array('engine'=>'Innodb', 'collation' => 'utf8mb4_general_ci', 'comment' => ' 微信用户表', 'signed' => false));
        $table->addColumn('nickname', 'string',array('limit'  =>  30,'default'=>'','comment'=>'用户名'))
            ->addColumn('avatar', 'string',array('limit'  =>  255,'comment'=>'用户头像'))
            ->addColumn('openid', 'string',array('limit'  =>  35, 'comment'=>'openid'))
            ->addColumn('language', 'string',array('limit'  =>  20, 'comment'=>'语言'))
            ->addColumn('country', 'string',array('limit'  =>  20, 'comment'=>'国家'))
            ->addColumn('province', 'string',array('limit'  =>  20, 'comment'=>'省份'))
            ->addColumn('city', 'string',array('limit'  =>  20, 'comment'=>'城市'))
            ->addColumn('subscribe', 'boolean',array('limit'  =>  1,'default'=> 1,'comment'=>'用户状态  0 取消订阅  1 订阅'))
            ->addColumn('block', 'boolean',array('limit'  =>  1,'default'=> 1,'comment'=>'拉黑状态  1 正常  2 拉黑'))
            ->addColumn('subscribe_time', 'integer',array('default'=>0,'comment'=>'订阅时间', 'signed' => false))
            ->addColumn('subscribe_scene', 'string', ['limit' => 50, 'comment' => '订阅场景 ADD_SCENE_SEARCH 公众号搜索，ADD_SCENE_ACCOUNT_MIGRATION 公众号迁移，ADD_SCENE_PROFILE_CARD 名片分享，ADD_SCENE_QR_CODE 扫描二维码，ADD_SCENE_PROFILE_LINK 图文页内名称点击，ADD_SCENE_PROFILE_ITEM 图文页右上角菜单，ADD_SCENE_PAID 支付后关注，ADD_SCENE_WECHAT_ADVERTISEMENT 微信广告，ADD_SCENE_OTHERS 其他'])
            ->addColumn('unionid', 'string', ['limit' => 255, 'comment' => '用户平台唯一身份认证'])
            ->addColumn('sex', 'boolean',array('limit'  =>  1,'default'=> 1,'comment'=>'用户状态 1 男 2 女 0 未知'))
            ->addColumn('remark', 'string', ['limit' => 255, 'comment' => '备注'])
            ->addColumn('groupid', 'integer', ['limit' => 0, 'comment' => '分组ID'])
            ->addColumn('tagid_list', 'string',['limit' => 50, 'default'=>0,'comment'=>'标签列表'])
            ->addColumn('created_at', 'integer', array('default'=>0,'comment'=>'创建时间', 'signed' => false ))
            ->addColumn('updated_at', 'integer', array('default'=>0,'comment'=>'更新时间', 'signed' => false))
            ->addColumn('deleted_at', 'integer', array('default'=>0,'comment'=>'删除状态，0未删除 >0 已删除', 'signed' => false))
            ->create();
    }
}

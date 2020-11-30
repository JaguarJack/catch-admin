<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~{$year} http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

use think\migration\Seeder;

class TradeMenusSeed extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        \catcher\Utils::importTreeData($this->getPermissions(), 'permissions', 'parent_id');
    }

    protected function getPermissions()
    {
        return array(
            0 =>
            array(
                'id' => 79,
                'permission_name' => '交易管理',
                'route' => '/trade',
                'url' => '',
                'component' => 'layout',
                'parent_id' => 0,
                'level' => '',
                'icon' => 'el-icon-money',
                'module' => 'trade',
                'creator_id' => 1,
                'permission_mark' => 'trade',
                'redirect' => '',
                'keepalive' => 1,
                'type' => 1,
                'hidden' => 1,
                'sort' => 799,
                'created_at' => 1606482236,
                'updated_at' => 1606482559,
                'deleted_at' => 0,
                'children' =>
                array(
                    0 =>
                    array(
                        'id' => 87,
                        'permission_name' => '充值管理',
                        'route' => '/trade/recharge',
                        'url' => '/finance/recharge',
                        'component' => 'formTable',
                        'parent_id' => 79,
                        'level' => '79',
                        'icon' => '',
                        'module' => 'trade',
                        'creator_id' => 1,
                        'permission_mark' => 'tradeRecharge',
                        'redirect' => '',
                        'keepalive' => 1,
                        'type' => 1,
                        'hidden' => 1,
                        'sort' => 1,
                        'created_at' => 1606484031,
                        'updated_at' => 1606486550,
                        'deleted_at' => 0,
                    ),
                    1 =>
                    array(
                        'id' => 88,
                        'permission_name' => '提现管理',
                        'route' => '/trade/withdrawal',
                        'url' => '/finance/withdrawal',
                        'component' => 'formTable',
                        'parent_id' => 79,
                        'level' => '79',
                        'icon' => '',
                        'module' => 'trade',
                        'creator_id' => 1,
                        'permission_mark' => 'tradeWithdrawal',
                        'redirect' => '',
                        'keepalive' => 1,
                        'type' => 1,
                        'hidden' => 1,
                        'sort' => 1,
                        'created_at' => 1606484059,
                        'updated_at' => 1606486544,
                        'deleted_at' => 0,
                    ),
                    2 =>
                    array(
                        'id' => 94,
                        'permission_name' => '交易配置',
                        'route' => '/trade/config',
                        'url' => '/trade/config',
                        'component' => 'tradeConfig',
                        'parent_id' => 79,
                        'level' => '79',
                        'icon' => '',
                        'module' => 'trade',
                        'creator_id' => 1,
                        'permission_mark' => 'tradeConfig',
                        'redirect' => '',
                        'keepalive' => 1,
                        'type' => 1,
                        'hidden' => 1,
                        'sort' => 1,
                        'created_at' => 1606485326,
                        'updated_at' => 1606486564,
                        'deleted_at' => 0,
                    ),
                ),
            ),
        );
    }
}

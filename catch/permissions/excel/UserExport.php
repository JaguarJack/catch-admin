<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catchAdmin\permissions\excel;

use catchAdmin\permissions\model\Users;
use catcher\library\excel\ExcelContract;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UserExport implements ExcelContract
{

    /**
     * 设置头部
     *
     * @time 2020年09月08日
     * @return string[]
     */
    public function headers(): array
    {
        // TODO: Implement headers() method.
        return [
            'id', '用户名', '邮箱', '状态', '创建日期'
        ];
    }

    /**
     * 处理数据
     *
     * @time 2020年09月08日
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return \think\Collection
     */
    public function sheets()
    {
        // TODO: Implement sheets() method.
        $users = Users::field(['id', 'username', 'email', 'status', 'created_at'])->select();

        foreach ($users as &$user) {
            $user->status = $user->status == Users::ENABLE ? '启用' : '停用';
        }

        return $users;
    }

    /**
     * 设置开始行
     *
     * @time 2020年09月08日
     * @return int
     */
    public function setRow()
    {
        return 2;
    }

    /**
     * 设置标题
     *
     * @time 2020年09月08日
     * @return array
     */
    public function setTitle()
    {
        return [
            'A1:G1', '导出用户', Alignment::HORIZONTAL_CENTER
        ];
    }
}
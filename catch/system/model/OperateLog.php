<?php

namespace catchAdmin\system\model;

use catchAdmin\permissions\model\Users;
use catcher\traits\db\BaseOptionsTrait;
use catchAdmin\system\model\search\OperateLogSearch;

class OperateLog extends \think\Model
{
    use BaseOptionsTrait;
    use OperateLogSearch;


    protected $name = 'operate_log';

    protected $field = [
        'id', // 
        'module', // 模块名称
        'operate', // 操作模块
        'route', // 路由
        'params', // 参数
        'ip', // ip
        'creator_id', // 创建人ID
        'method', // 请求方法
        'created_at', // 登录时间   
    ];

    /**
     * get list
     *
     * @time 2020年04月28日
     * @param $params
     * @throws \think\db\exception\DbException
     * @return void
     */
    public function getList()
    {
        return $this->field([$this->aliasField('*')])
            ->catchJoin(Users::class, 'id', 'creator_id', ['username as creator'])
            ->catchSearch()
            ->order($this->aliasField('id'), 'desc')
            ->paginate();
    }
}

<?php

namespace Modules\Common\Http\Controllers;

use Exception;
use Illuminate\Support\Collection;
use Modules\Common\Repository\Options\Factory;

/**
 * @group 公共模块
 *
 * @subgroup 选项管理
 * @subgroupDescription CatchAdmin 后台选项管理
 */
class OptionController
{
    /**
     * 选项列表
     *
     * @urlParam name string required 选项名称
     *
     * @responseField data object[] 选项数据
     * @responseField data[].value string|int 选项值
     * @responseField data[].name string 选项名称
     *
     * @throws Exception
     */
    public function index($name, Factory $factory): array|Collection
    {
        return $factory->make($name)->get();
    }
}

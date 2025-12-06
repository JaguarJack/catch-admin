<?php

namespace Modules\Common\Http\Controllers;

use Exception;
use Modules\Common\Models\Area;

/**
 * @group 公共模块
 *
 * @subgroup 地区管理
 * @subgroupDescription CatchAdmin 后台地区管理
 */
class AreaController
{
    /**
     * 地区列表
     *
     * @responseField id int 地区ID
     * @responseField name string 地区名称
     * @responseField parent_id int 父级ID
     * @responseField children object[] 子级
     * @responseField children[].id int 地区ID
     * @responseField children[].name string 地区名称
     * @responseField children[].parent_id int 父级ID
     *
     * @throws Exception
     */
    public function index(Area $area)
    {
        return $area->getAll();
    }
}

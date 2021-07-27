<?php
// +----------------------------------------------------------------------
// | UCToo [ Universal Convergence Technology ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2021 https://www.uctoo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: UCToo <contact@uctoo.com>
// +----------------------------------------------------------------------

namespace catchAdmin\apimanager\model;

use catchAdmin\permissions\model\DataRangScopeTrait;
use catchAdmin\permissions\model\Users;
use catchAdmin\apimanager\model\search\ApiTesterUserenvSearch;
use catcher\base\CatchModel as Model;
/**
 *
 * @property int $id
 * @property string $env_name
 * @property string $appid
 * @property string $project_id
 * @property string $env_json
 * @property int $selected
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $creator_id
 */
class ApiTesterUserenv extends Model
{
    use ApiTesterUserenvSearch;
    use DataRangScopeTrait;
    // 表名
    public $name = 'api_tester_userenv';
    // 数据库字段映射
    public $field = array(
        'id',
        // 环境名称
        'env_name',
        // appid
        'appid',
        // 项目ID
        'project_id',
        // 环境变量json
        'env_json',
        // 是否当前选中:0=否,1=是
        'selected',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除字段
        'deleted_at',
        // 创建人ID
        'creator_id',
    );

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
        return $this->dataRange()->field([$this->aliasField('*')])
            ->catchJoin(Users::class, 'id', 'creator_id', ['username as creator'])
            ->catchSearch()
            ->order($this->aliasField('id'), 'desc')
            ->paginate();
    }
}
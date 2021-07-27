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

use catcher\base\CatchModel as Model;
/**
 *
 * @property int $id
 * @property string $appid
 * @property int $user_id
 * @property int $api_id
 * @property string $params
 * @property string $result
 * @property string $request_data
 * @property string $response_data
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $creator_id
 */
class ApiTesterLog extends Model
{
    // 表名
    public $name = 'api_tester_log';
    // 数据库字段映射
    public $field = array(
        'id',
        // appid
        'appid',
        // users表id
        'user_id',
        // api_tester表id
        'api_id',
        // api参数
        'params',
        // 返回值
        'result',
        // 请求数据
        'request_data',
        // 响应数据
        'response_data',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除字段
        'deleted_at',
        // 创建人ID
        'creator_id',
    );
}
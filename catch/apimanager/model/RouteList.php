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

use catchAdmin\apimanager\model\search\RouteListSearch;
use catcher\base\CatchModel as Model;
/**
 *
 * @property int $id
 * @property string $rule
 * @property string $route
 * @property string $method
 * @property string $name
 * @property string $domain
 * @property string $option
 * @property string $pattern
 * @property string $title
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $creator_id
 */
class RouteList extends Model
{
    use RouteListSearch;
    public $field = [
        //
        'id',
        //
        'rule',
        //
        'route',
        //
        'method',
        //
        'name',
        //
        'domain',
        //
        'option',
        //
        'pattern',
        //
        'title',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除字段
        'deleted_at',
        // 创建人ID
        'creator_id',
    ];
    
    public $name = 'route_list';

}
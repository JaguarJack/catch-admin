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
use catchAdmin\apimanager\model\search\ApiTesterSearch;
/**
 *
 * @property int $id
 * @property string $api_title
 * @property string $api_name
 * @property int $category_id
 * @property int $type
 * @property string $appid
 * @property string $project_id
 * @property string $api_url
 * @property string $methods
 * @property string $auth
 * @property string $header
 * @property string $query
 * @property string $body
 * @property string $doc_url
 * @property string $document
 * @property string $sample_data
 * @property string $sample_result
 * @property int $sort
 * @property int $status
 * @property string $content_type
 * @property int $env_id
 * @property string $memo
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $creator_id
 */
class ApiTester extends Model
{
    use ApiTesterSearch;
    // 表名
    public $name = 'api_tester';
    // 数据库字段映射
    public $field = array(
        'id',
        // 标题
        'api_title',
        // 英文唯一标识
        'api_name',
        // 分类
        'category_id',
        // 数据源类型:1=remote,2=local
        'type',
        // appid
        'appid',
        // 项目ID
        'project_id',
        // API URL
        'api_url',
        // 方法:POST,GET,PUT,PATCH,DELETE,COPY,HEAD,OPTIONS
        'methods',
        // 鉴权
        'auth',
        // header
        'header',
        // query
        'query',
        // body
        'body',
        // 文档URL
        'doc_url',
        // 文档
        'document',
        // 示例数据
        'sample_data',
        // 示例返回数据
        'sample_result',
        // 排序
        'sort',
        // 状态:1=已完成,2=待开发,3=开发中,4=已废弃
        'status',
        // content-type:application/x-www-form-urlencoded,multipart/form-data,raw
        'content_type',
        // 环境ID
        'env_id',
        // 备注
        'memo',
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
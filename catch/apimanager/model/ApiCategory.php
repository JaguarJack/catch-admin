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

use catchAdmin\apimanager\model\search\ApiCategorySearch;
use catcher\base\CatchModel as Model;
use think\db\exception\DbException;
/**
 *
 * @property int $id
 * @property string $category_title
 * @property int $parent_id
 * @property string $category_name
 * @property int $status
 * @property int $sort
 * @property int $created_at
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $creator_id
 */
class ApiCategory extends Model
{
    use ApiCategorySearch;
    // 表名
    public $name = 'api_category';
    // 数据库字段映射
    public $field = array(
        'id',
        // 分类标题
        'category_title',
        // 父级ID
        'parent_id',
        // 分类唯一标识
        'category_name',
        // 状态:1=正常;2=停用
        'status',
        // 排序字段
        'sort',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除字段
        'deleted_at',
        // 创建人ID
        'creator_id',
    );

    protected $updateChildrenFields = 'status';

    /**
     * 列表数据
     *
     * @time 2020年01月09日
     * @return array
     * @throws DbException
     */
    public function getList(): array
    {
        return $this->catchSearch()
            ->catchOrder()
            ->select()->toTree();
    }

    /**
     * 获取子分类IDS
     *
     * @time 2020年11月04日
     * @param $id
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @return mixed
     */
    public static function getChildrenCategoryIds($id)
    {
        $categoryIds = ApiCategory::field(['id', 'parent_id'])->select()->getAllChildrenIds([$id]);

        $categoryIds[] = $id;

        return $categoryIds;
    }
}
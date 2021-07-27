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

namespace catchAdmin\apimanager\model\search;

use catchAdmin\apimanager\model\ApiCategory;

trait ApiTesterSearch
{
    public function searchApiTitleAttr($query, $value, $data)
    {
        return $query->whereLike('api_title', $value);
    }

    public function searchApiNameAttr($query, $value, $data)
    {
        return $query->whereLike('api_name', $value);
    }

    public function searchStatusAttr($query, $value, $data)
    {
        return $query->where($this->aliasField('status'), $value);
    }

    public function searchTypeAttr($query, $value, $data)
    {
        return $query->where($this->aliasField('type'), $value);
    }

    /**
     * 查询分类下的API
     *
     * @time 2021年05月20日
     * @param $query
     * @param $value
     * @param $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return mixed
     */
    public function searchCategoryIdAttr($query, $value, $data)
    {
        return $query->whereIn($this->aliasField('category_id'), ApiCategory::getChildrenCategoryIds($value));
    }
}

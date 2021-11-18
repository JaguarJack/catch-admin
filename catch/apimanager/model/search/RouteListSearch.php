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

trait RouteListSearch
{
    public function searchRuleAttr($query, $value, $data)
    {
        return $query->whereLike('rule', $value);
    }

    public function searchRouteAttr($query, $value, $data)
    {
        return $query->whereLike('route', $value);
    }

    public function searchMethodAttr($query, $value, $data)
    {
        return $query->whereLike('method', $value);
    }

    public function searchNameAttr($query, $value, $data)
    {
        return $query->whereLike('name', $value);
    }
    public function searchDomainAttr($query, $value, $data)
    {
        return $query->whereLike('domain', $value);
    }
    public function searchOptionAttr($query, $value, $data)
    {
        return $query->whereLike('option', $value);
    }
    public function searchPatternAttr($query, $value, $data)
    {
        return $query->whereLike('pattern', $value);
    }

}

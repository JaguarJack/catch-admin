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

use catchAdmin\permissions\model\Users;

trait ApiTesterUserenvSearch
{
    public function searchCreatorAttr($query, $value, $data)
    {
        return $query->whereLike(app(Users::class)->getTable() . '.username', $value);
    }

    public function searchEnvNameAttr($query, $value, $data)
    {
        return $query->whereLike('env_name', $value);
    }

}

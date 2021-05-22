<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\cms\model\scopes;

use catchAdmin\cms\model\Articles;
use think\facade\Db;

trait CategoryScope
{
    /**
     * 文章数量
     *
     * @time 2020年06月17日
     * @param $query
     * @return mixed
     */
    public function scopeArticlesCount($query)
    {
        return $query->addSelectSub(function () {
            $article = app(Articles::class);
            return $article->field(Db::raw('count(*)'))
                ->whereColumn($this->aliasField('id'), $article->aliasField('category_id'));
        }, 'articles');
    }
}

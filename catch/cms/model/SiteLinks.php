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

namespace catchAdmin\cms\model;

class SiteLinks extends BaseModel
{
    // 表名
    public $name = 'cms_site_links';
    // 数据库字段映射
    public $field = array(
        'id',
        // 友情链接标题
        'title',
        // 跳转地址
        'link_to',
        // 权重
        'weight',
        // 1 展示 2 隐藏
        'is_show',
        // 网站图标
        'icon',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );

    /**
     * 标题搜索
     *
     * @time 2021年05月09日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchTitleAttr($query, $value, $data)
    {
        return $query->whereLike('title', $value);
    }
}
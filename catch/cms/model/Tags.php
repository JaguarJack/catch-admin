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

use catchAdmin\cms\model\events\TagsEvent;

class Tags extends BaseModel
{
    use TagsEvent;

    // 表名
    public $name = 'cms_tags';
    // 数据库字段映射
    public $field = array(
        'id',
        // 标签名称
        'name',
        // seo 标签
        'title',
        // 关键字
        'keywords',
        // 描述
        'description',
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
     * 列表
     *
     * @auth CatchAdmin
     * @time 2021年05月22日
     * @return mixed
     * @throws \think\db\exception\DbException
     */
    public function getList()
    {
        // 分页列表
        return $this->catchSearch()
            ->field($this->aliasField('*'))
            ->withCount('articles')
            ->catchOrder()
            ->paginate();
    }

    /**
     * 标签下的文章
     *
     * @author CatchAdmin
     * @time 2021年05月24日
     * @return \think\model\relation\BelongsToMany
     */
    public function articles(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Articles::class,  'cms_article_relate_tags',
            'article_id', 'tag_id'
        );
    }

    /**
     * 标签 name 搜索
     *
     * @author CatchAdmin
     * @time 2021年05月24日
     * @param $query
     * @param $value
     * @return void
     */
    public function searchNameAttr($query, $value)
    {
        $query->whereLike('name', $value);
    }
}
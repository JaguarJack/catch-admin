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

use catchAdmin\cms\model\events\CategoryEvent;
use catchAdmin\cms\model\scopes\CategoryScope;

class Category extends BaseModel
{
    use CategoryEvent, CategoryScope;

    // 表名
    public $name = 'cms_category';
    // 数据库字段映射
    public $field = array(
        'id',
        // 分类名称
        'name',
        // 父级ID
        'parent_id',
        // seo标题
        'title',
        // seo关键词
        'keywords',
        // 描述
        'description',
        // 自定义 URL
        'url',
        // 1 显示 2 隐藏
        'status',
        // 是否可以投稿
        'is_can_contribute',
        // 是否可以评论
        'is_can_comment',
        // 是否是单页面 1 是 2 否
        'type',
        // 权重
        'weight',
        // 1 是 2 否
        'is_link_out',
        // 链接外部地址
        'link_to',
        // 创建人
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );

    const LIST_TYPE = 1; // 列表
    const PAGE_TYPE = 2; // 单页
    const COVER_TYPE = 3; // 封面

    const CAN_COMMENT = 1; // 可以评论
    const CAN_NOT_COMMENT = 2; // 不可以评论

    const CAN_CONTRIBUTE = 1; // 可以投稿
    const CAN_NOT_CONTRIBUTE = 2; // 不可以投稿

    /**
     * 列表
     *
     * @time 2021年03月03日
     * @return mixed
     */
    public function getList()
    {
        return $this->quickSearch()
            ->field(['*'])
            ->catchOrder()
            ->articlesCount()
            ->select()->toTree();
    }

    /**
     * 是否存在下级
     *
     * @time 2021年03月03日
     * @param int $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|\think\Model|null
     */
    public function hasNextLevel($id = 0)
    {
        return $this->where('parent_id', $id ? :$this->getKey())->find();
    }
}
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

use catchAdmin\cms\model\events\ArticlesEvent;

/**
 * Class Articles
 * @package catchAdmin\cms\model
 * @auth CatchAdmin
 * @time 2021年05月22日
 *
 * @property Articles category_id
 * @property Articles id
 * @property Articles title
 * @property Articles cover
 * @property Articles images
 * @property Articles tags
 * @property Articles url
 * @property Articles content
 * @property Articles keywords
 * @property Articles description
 * @property Articles pv
 * @property Articles likes
 * @property Articles comments
 * @property Articles is_top
 * @property Articles is_recommend
 * @property Articles status
 * @property Articles weight
 * @property Articles is_can_comment
 * @property Articles creator_id
 * @property Articles created_at
 * @property Articles updated_at
 * @property Articles deleted_at
 *
 */
class Articles extends BaseModel
{
    use ArticlesEvent;

    // 表名
    public $name = 'cms_articles';
    // 数据库字段映射
    public $field = array(
        'id',
        // 文章标题
        'title',
        // 分类ID
        'category_id',
        'cover', // 封面
        // 多图集合
        'images',
        // 自定义URL
        'url',
        // 内容
        'content',
        // 关键字
        'keywords',
        // 描述
        'description',
        // 浏览量
        'pv',
        // 喜欢
        'likes',
        // 评论数
        'comments',
        // 1 置顶 2 非置顶
        'is_top',
        // 1 推荐 2 不推荐
        'is_recommend',
        // 1 展示 2 隐藏
        'status',
        'weight', // 权重
        // 1 允许 2 不允许
        'is_can_comment',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );

    const TOP = 1; // 置顶
    const UN_TOP = 2; // 不置顶

    const RECOMMEND = 1; // 推荐
    const UN_RECOMMEND = 2; // 不推荐

    const CAN_COMMENT = 1; // 评论允许
    const UN_CAN_COMMENT = 2; // 评论不允许

    /**
     * 列表
     *
     * @auth CatchAdmin
     * @time 2021年05月22日
     * @return mixed
     */
    public function getList()
    {
        // 分页列表
        return $this->catchSearch()
            ->field($this->aliasField('*'))
            ->catchJoin(Category::class, 'id', 'category_id', ['name as category'])
            ->catchOrder()
            ->creator()
            ->paginate();
    }

    /**
     * 查询
     *
     * @auth CatchAdmin
     * @time 2021年05月22日
     * @param $id
     * @param array|string[] $field
     * @param bool $trash
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|mixed|\think\Model|null
     */
    public function findBy($id, array $field = ['*'], bool $trash = false)
    {
        return $this->where('id', $id)
                    ->with(['tag'])
                    ->field('*')
                    ->find();
    }

    /**
     * 文章标签
     *
     * @time 2021年05月17日
     * @return \think\model\relation\BelongsToMany
     */
    public function tag(): \think\model\relation\BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'cms_article_relate_tags',
            'tag_id', 'article_id'
        );
    }

    /**
     * 删除标签
     *
     * @param array $ids
     * @return int
     * @author CatchAdmin
     * @time 2021年05月22日
     */
    public function detachTags(array $ids = []): int
    {
        return $this->tag()->detach($ids);
    }

    /**
     * 新增标签
     *
     * @author CatchAdmin
     * @time 2021年05月22日
     * @param array $ids
     * @throws \think\db\exception\DbException
     * @return array|\think\model\Pivot
     */
    public function attachTags(array $ids)
    {
        return $this->tag()->attach($ids);
    }
}
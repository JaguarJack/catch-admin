<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catchAdmin\wechat\repository;

use catcher\base\CatchRepository;
use catchAdmin\wechat\model\WechatGraphic;
use catcher\exceptions\FailedException;

class WechatGraphicRepository extends CatchRepository
{
    protected $wechatGraphic;

    public function __construct(WechatGraphic $graphic)
    {
        $this->wechatGraphic = $graphic;
    }

    protected function model()
    {
        return $this->wechatGraphic;
    }

    /**
     * 获取
     *
     * @time 2020年06月27日
     * @param array $params
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return mixed
     */
    public function getList(array $params)
    {
        // 父级
        $list = $this->wechatGraphic
                     ->where('parent_id', 0)
                     ->paginate();
        // 子级
        $subList = $this->wechatGraphic
                        ->whereIn('parent_id', $list->column('id'))
                        ->select();

        foreach ($list as &$item) {
            $item->children = $subList->where('parent_id', $item->id);
        }

        return $list;
    }

    /**
     * 更新
     *
     * @time 2020年06月27日
     * @param array $data
     * @return mixed
     */
    public function storeBy(array $data)
    {
        $creatorId = $data['creator_id'];
        $articles = $data['articles'];

        $first = array_shift($articles);
        $first['creator_id'] = $creatorId;
        $parentId = parent::storeBy($first);

        foreach ($articles as &$article) {
            $article['parent_id'] = $parentId;
            $article['creator_id'] = $creatorId;
            $article['created_at'] = $article['updated_at'] = time();
        }

        return $this->wechatGraphic->insertAll($articles);
    }

    /**
     * 查找
     *
     * @time 2020年06月28日
     * @param int $id
     * @param array|string[] $column
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array
     */
    public function findBy(int $id, array $column = ['*'])
    {
        $field = ['id', 'title', 'author', 'cover', 'content'];

        $article = [parent::findBy($id, $field)->toArray()];

        return array_merge($article, $this->wechatGraphic->field($field)->where('parent_id', $id)->select()->toArray());
    }

    /**
     * 更新
     *
     * @time 2020年06月28日
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateBy(int $id, array $data)
    {
        try {
            $this->wechatGraphic->startTrans();
            if (!parent::deleteBy($id)) {
                throw new FailedException('更新失败');
            }

            if ($this->wechatGraphic->where('parent_id', $id)->find() && !$this->wechatGraphic->where('parent_id', $id)->delete()) {
                throw new FailedException('更新失败');
            }

            foreach ($data['articles'] as &$article) {
                unset($article['id']);
            }

            if ($this->storeBy($data) === false) {
                throw new FailedException('更新失败');
            }
        } catch (\Exception $exception) {
            $this->wechatGraphic->rollback();
            throw new FailedException($exception->getMessage());
        }

        $this->wechatGraphic->commit();
        return true;
    }


    /**
     * 删除
     *
     * @time 2020年06月28日
     * @param int $id
     * @return bool
     */
    public function deleteBy(int $id)
    {
        if (parent::deleteBy($id)) {
           return $this->wechatGraphic
                        ->where('parent_id', $id)
                        ->delete();
        }
        throw new FailedException('删除失败');
    }
}
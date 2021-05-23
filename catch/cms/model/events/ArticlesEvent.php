<?php
namespace catchAdmin\cms\model\events;

use catchAdmin\cms\model\Articles;
use catchAdmin\cms\model\BaseModel;
use catchAdmin\cms\model\Tags;
use catcher\exceptions\FailedException;
use catcher\Utils;

trait ArticlesEvent
{
    /**
     * 插入前
     *
     * @time 2021年05月21日
     * @param Articles $model
     * @return void
     */
    public static function onBeforeInsert(Articles $model)
    {
        self::beforeChangeData($model);
    }


    /**
     * 插入后
     *
     * @time 2021年05月21日
     * @param Articles $model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return void
     */
    public static function onAfterInsert(Articles $model)
    {
        $model->attachTags(self::getTagsId($model));
    }


    /**
     *
     * @auth CatchAdmin
     * @time 2021年05月22日
     * @param Articles $model
     * @return void
     */
    public static function onBeforeUpdate(Articles $model)
    {
        self::beforeChangeData($model);
    }

    /**
     *
     * @auth CatchAdmin
     * @time 2021年05月22日
     * @param Articles $model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return void
     */
    public static function onAfterUpdate(Articles $model)
    {
        $data = $model->getData();

        if (isset($data['tags'])) {

            $tagIds = self::getTagsId($model);

            $article = $model->where($model->getWhere())->find();

            $article->detachTags($article->tag()->select()->column('id'));

            $article->attachTags(self::getTagsId($model));
        }
    }

    /**
     * 插入前
     *
     * @auth CatchAdmin
     * @time 2021年05月22日
     * @param $model
     * @return void
     */
    protected static function beforeChangeData($model)
    {
        $data = $model->getData();

        if (isset($data['category_id'])) {
            $model->category_id = $model->category_id[count($model->category_id) - 1];
        }

        if (isset($data['images'])) {
            $model->images = empty($model->images) ? '' : implode(',', $model->images);
        }

        if (isset($data['tags'])) {
            $model->tags = empty($model->tags) ? '' : implode(',', $model->tags);
        }
    }

    /**
     * 插入后
     *
     * @auth CatchAdmin
     * @time 2021年05月22日
     * @param $model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array
     */
    protected static function getTagsId($model): array
    {
        $tags = Utils::stringToArrayBy($model->tags);

        $tagIds = [];
        foreach ($tags as $tag) {
            $tagModel = Tags::where('name', $tag)->findOrEmpty();

            if ($tagModel->isEmpty()) {
               $tagIds[] = $tagModel->storeBy([
                    'name' => $tag
                ]);
            }
        }

        return array_merge(Tags::whereIn('name', $tags)->column('id'), $tagIds);
    }
}
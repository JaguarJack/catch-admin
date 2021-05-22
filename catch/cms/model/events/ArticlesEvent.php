<?php
namespace catchAdmin\cms\model\events;

use catchAdmin\cms\model\Articles;
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
        $model->category_id = $model->category_id[0];

        $model->images = implode(',', $model->images);

        $model->tags = implode(',', $model->tags);
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
        $tags = Utils::stringToArrayBy($model->tags);

        $tagModel = new Tags;
        $existTags = $tagModel->whereIn('name', $tags)->select()->toArray();
        $existTagsName = array_column($existTags, 'name');

        $tagsIds = [];
        foreach ($tags as $tag) {
            if (! in_array($tag, $existTagsName)) {
                $tagsIds[] = $tagModel->createBy([
                    'name' => $tag
                ]);
            }
        }


        $model->tags()->attach(array_merge(array_column($existTags, 'id'), $tagsIds));
    }
}
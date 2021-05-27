<?php
namespace catchAdmin\cms\model\events;

use catchAdmin\cms\model\Banners;

trait BannersEvent
{
    /**
     * 插入前
     *
     * @time 2021年05月21日
     * @param Banners $model
     * @return void
     */
    public static function onBeforeInsert(Banners $model)
    {
        self::beforeChangeData($model);
    }


    /**
     *
     * @auth CatchAdmin
     * @time 2021年05月22日
     * @param Banners $model
     * @return void
     */
    public static function onBeforeUpdate(Banners $model)
    {
        self::beforeChangeData($model);
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
            $model->category_id = is_array($model->category_id) ?

                (count($model->category_id) ? $model->category_id[count($model->category_id) - 1] : 0)

                : $model->category_id;
        }
    }
}
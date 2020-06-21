<?php
/**
 * @filename  UserSearchTrait.php
 * @createdAt 2020/6/21
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catchAdmin\wechat\model\search;

trait UserSearchTrait
{
    /**
     * 昵称搜索
     * @time 2020年06月21日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchNicknameAttr($query, $value, $data)
    {
        return $query->whereLike('nickname', $value);
    }

    /**
     * 拉黑
     *
     * @time 2020年06月21日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchBlockAttr($query, $value, $data)
    {
        return $query->where('block', $value);
    }

    /**
     * 订阅
     *
     * @time 2020年06月21日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchSubscribeAttr($query, $value, $data)
    {
        return $query->where('subscribe', $value);
    }

    /**
     * 订阅开始
     *
     * @time 2020年06月21日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchStartAtAttr($query, $value, $data)
    {
        return $query->where('subscribe_time', '>=', strtotime($value . ' 00:00:00'));
    }

    /**
     * 订阅结束
     *
     * @time 2020年06月21日
     * @param $query
     * @param $value
     * @param $data
     * @return mixed
     */
    public function searchEndAtAttr($query, $value, $data)
    {
        return $query->where('subscribe_time', '<=', strtotime($value . ' 23:59:59'));
    }
}
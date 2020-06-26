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
namespace catchAdmin\wechat\library\messages;

class Factory
{
    /**
     * 对象生产
     *
     * @time 2020年06月26日
     * @param $message
     * @return mixed
     */
    public static function make($message)
    {
        return self::parse($message);
    }

    /**
     * 解析
     *
     * @time 2020年06月26日
     * @param $message
     * @return mixed
     */
    protected static function parse($message)
    {
        // 事件类型
        if ($message['MsgType'] == 'event') {
            $event = __NAMESPACE__ . '\\events\\' . ucfirst($message['Event']);

            return new $event($message);
        }

        $messageClass = __NAMESPACE__ . '\\' . ucfirst($message['MsgType']);
        return new $messageClass($message);
    }
}
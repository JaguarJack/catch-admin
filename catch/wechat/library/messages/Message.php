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

use think\helper\Str;

abstract class Message
{
    protected $message;

    public function __construct(array $message)
    {
        $this->message = $message;
    }

    /**
     * 接收方账号
     *
     * @time 2020年06月26日
     * @return mixed
     */
    protected function toUserName()
    {
        return $this->message['ToUserName'];
    }

    /**
     * 发送方账号
     *
     * @time 2020年06月26日
     * @return mixed
     */
    protected function fromUserName()
    {
        return $this->message['FromUserName'];
    }

    abstract public function reply();

    /**
     * 访问消息内容
     *
     * @time 2020年06月26日
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->message[Str::camel($name)];
    }

    /**
     * 访问消息内容
     *
     * @time 2020年06月26日
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return $this->message[lcfirst($name)];
    }
}
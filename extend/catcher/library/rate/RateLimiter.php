<?php
// +----------------------------------------------------------------------
declare(strict_types=1);

/*
 * @Application ：interfaceSkeleton
 * @Version     : 1.0.0
 * @Author      : @小小只 <soinco@qq.com>
 * @Since       : 2020-11-03 17:20:50
 * @LastAuthor  : @小小只 <soinco@qq.com>
 * @lastTime    : 2020-11-03 17:24:26
 * @Github      : https://github.com/littlezo
 * @Copyright   : 2017-2020 @小小只
 * @联系方式    : QQ:970055999  WeChat: littlezov email: soinco@qq.com
 * @版权声明    : @小小只 拥有所有权利,未取得授权禁止任何形式的代码拷贝、派生行为 如需引用 需取得所有者授权方可
 * @FilePath    : \\extend\\catcher\\library\\rate\\RateLimiter.php
 */
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher\library\rate;

use catcher\exceptions\FailedException;

class RateLimiter
{
    use Redis;

    protected $key;

    /**
     * 令牌容量
     *
     * @var int
     */
    protected $capacity = 5;

    /**
     * 每次添加 token 的数量
     *
     * @var int
     */
    protected $eachTokens = 5;

    /**
     * 添加 token 的时间
     *
     * @var string
     */
    protected $addTokenTimeKey = '_add_token';

    /**
     * 添加 token 的时间间隔 /s
     *
     * @var int
     */
    protected $interval = 5;

    /**
     * RateLimiter constructor.
     * @param $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * 处理
     *
     * @time 2020年07月02日
     * @return void
     */
    public function overflow()
    {
        // 添加 token
        if ($this->canAddToken()) {
            $this->addTokens();
        }

        if (!$this->tokens()) {
            throw new FailedException('访问限制');
        }

        // 每次请求拿走一个 token
        $this->removeToken();
    }

    /**
     *
     *
     * @time 2020年07月02日
     * @return void
     */
    protected function addTokens()
    {
        $leftTokens = $this->capacity - $this->tokens();

        $tokens = array_fill(0, $leftTokens < $this->eachTokens ? $leftTokens : $this->eachTokens, 1);

        $this->getRedis()->lPush($this->key, ...$tokens);

        $this->rememberAddTokenTime();
    }

    /**
     * 拿走一个 token
     *
     * @time 2020年07月02日
     * @return void
     */
    protected function removeToken()
    {
        $this->getRedis()->rPop($this->key);
    }

    /**
     * 设置令牌桶数量
     *
     * @time 2020年07月02日
     * @param $capacity
     * @return $this
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * 剩余的 token 数量
     *
     * @time 2020年07月02日
     * @return bool|int
     */
    protected function tokens()
    {
        return $this->getRedis()->lLen($this->key);
    }


    /**
     * 设置时间间隔
     *
     * @time 2020年07月02日
     * @param $seconds
     * @return $this
     */
    public function setInterval($seconds)
    {
        $this->interval = $seconds;

        return $this;
    }

    /**
     * 是否可以添加 token
     *
     * @time 2020年07月02日
     * @return bool
     */
    protected function canAddToken()
    {
        $currentTime = \time();

        $lastAddTokenTime = $this->getRedis()->get($this->key . $this->addTokenTimeKey);

        // 如果是满的 则不添加
        if ($this->tokens() == $this->capacity) {
            return false;
        }

        return ($currentTime - $lastAddTokenTime) > $this->interval;
    }

    /**
     * 记录添加 token 的时间
     *
     * @time 2020年07月02日
     * @return void
     */
    protected function rememberAddTokenTime()
    {
        $this->getRedis()->set($this->key . $this->addTokenTimeKey, time());
    }
}

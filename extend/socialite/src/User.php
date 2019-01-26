<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 9:08
 */
namespace thinking\socialite;

class User
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $nickname;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $avatar;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $user;

    /**
     * get userId
     *
     * @time at 2018年12月29日
     * @return int
     */
    public function getUserId()
    {
        return (int)$this->id;
    }

    /**
     * get nickname
     *
     * @time at 2018年12月29日
     * @return string
     */
    public function getNickName()
    {
        return $this->nickname;
    }

    /**
     * get email
     *
     * @time at 2018年12月29日
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * get avatar
     *
     * @time at 2018年12月29日
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * get name
     *
     * @time at 2018年12月29日
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * set user
     *
     * @time at 2018年12月29日
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
       $this->user = $user;

       return $this;
    }

    /**
     * set property
     *
     * @time at 2018年12月29日
     * @param $user
     * @return $this
     */
    public function map($user)
    {
        foreach ($user as $attr => $value) {
             if ($this->hasProperty($attr)) {
                 $this->{$attr} = $value;
             }
        }

        return $this;
    }

    /**
     * has property
     *
     * @time at 2018年12月29日
     * @param $attr
     * @return bool
     */
    protected function hasProperty($attr)
    {
        return property_exists($this, $attr);
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->user[$name];
    }

    public function __isset($name)
    {
        // TODO: Implement __isset() method.
        return isset($this->user[$name]);
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->user[$name] = $value;
    }

    public function __unset($name)
    {
        // TODO: Implement __unset() method.
        unset($this->user[$name]);
    }

}
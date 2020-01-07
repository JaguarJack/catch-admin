<?php
namespace catcher;

use catcher\exceptions\FailedException;
use catcher\exceptions\LoginFailedException;
use thans\jwt\facade\JWTAuth;
use think\facade\Session;

class CatchAuth
{
    protected $auth;

    protected $guard;

    // 默认获取
    protected $username = 'email';
    // 校验字段
    protected $password = 'password';

    public function __construct()
    {
        $this->auth = config('catch.auth');

        $this->guard = $this->auth['default']['guard'];
    }

  /**
   * set guard
   *
   * @time 2020年01月07日
   * @param $guard
   * @return $this
   */
    public function guard($guard): self
    {
       $this->guard = $guard;

       return $this;
    }

    public function attempt($condition)
    {
        $user = $this->authenticate($condition);

        if (!$user) {
            throw new LoginFailedException();
        }

        if (!password_verify($condition['password'], $user->password)) {
            throw new LoginFailedException();
        }

        return $this->{$this->getDriver()}($user);
    }


    public function user()
    {
        switch ($this->getDriver()) {
          case 'jwt':
            $model = app($this->getProvider()['model']);
            return $model->where($model->getPk(), JWTAuth::auth()['id'])->find();
          case 'session':
            return Session::get($this->sessionUserKey(), null);
          default:
            throw new FailedException('user not found');
        }
    }

    public function logout()
    {
      
    }

    protected function jwt($user)
    {
        return JWTAuth::builder(['id' => $user->id]);
    }


    protected function session($user)
    {
        Session::set($this->sessionUserKey(), $user);
    }


    protected function sessionUserKey()
    {
      return $this->guard . '_user';
    }

    protected function getDriver()
    {
      return $this->auth['guards'][$this->guard]['driver'];
    }


    protected function getProvider()
    {
        return $this->auth['providers'][$this->auth['guards'][$this->guard]['provider']];
    }


    protected function authenticate($condition)
    {
        $provider = $this->getProvider();

        return $this->{$provider['driver']}($condition);
    }

  /**
   *
   * @time 2020年01月07日
   * @param $condition
   * @return void
   */
    protected function database($condition): void
    {}

  /**
   *
   * @time 2020年01月07日
   * @param $condition
   * @return mixed
   */
    protected function orm($condition)
    {
        return app($this->getProvider()['model'])->where($this->filter($condition))->find();
    }

  /**
   *
   * @time 2020年01月07日
   * @param $condition
   * @return array
   */
    protected function filter($condition): array
    {
        $where = [];

        foreach ($condition as $field => $value) {
          if ($field != $this->password) {
            $where[$field] = $value;
          }
        }

        return $where;
    }

  /**
   *
   * @time 2020年01月07日
   * @param $field
   * @return $this
   */
    public function username($field): self
    {
        $this->username = $field;

        return $this;
    }

  /**
   *
   * @time 2020年01月07日
   * @param $field
   * @return $this
   */
    public function password($field): self
    {
        $this->password = $field;

        return $this;
    }
}

<?php
declare(strict_types=1);

namespace catcher;

use catchAdmin\permissions\model\Users;
use catcher\exceptions\FailedException;
use catcher\exceptions\LoginFailedException;
use thans\jwt\facade\JWTAuth;
use think\facade\Session;
use think\helper\Str;

class CatchAuth
{
    /**
     * @var mixed
     */
    protected $auth;

    /**
     * @var mixed
     */
    protected $guard;

    // 默认获取
    protected $username = 'email';

    // 校验字段
    protected $password = 'password';

    // 保存用户信息
    protected $user = [];

    /**
     * @var bool
     */
    protected $checkPassword = true;

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
    public function guard($guard)
    {
        $this->guard = $guard;

        return $this;
    }

    /**
     *
     * @time 2020年01月07日
     * @param $condition
     * @return mixed
     */
    public function attempt($condition)
    {
        try {

            $user = $this->authenticate($condition);

            if (!$user) {
                throw new LoginFailedException();
            }
            if ($user->status == Users::DISABLE) {
                throw new LoginFailedException('该用户已被禁用|' . $user->username, Code::USER_FORBIDDEN);
            }

            if ($this->checkPassword && !password_verify($condition['password'], $user->password)) {
                throw new LoginFailedException('登录失败|' . $user->username);
            }

            return $this->{$this->getDriver()}($user);

        } catch (\Exception $exception) {
            //
        }
    }


    /**
     * user
     *
     * @time 2020年09月09日
     * @return mixed
     */
    public function user()
    {
        $user = $this->user[$this->guard] ?? null;

        if (!$user) {
            switch ($this->getDriver()) {
                case 'jwt':
                    $model = app($this->getProvider()['model']);
                    $user = $model->where($model->getPk(), JWTAuth::auth()[$this->jwtKey()])->find();
                    break;
                case 'session':
                    $user = Session::get($this->sessionUserKey(), null);
                    break;
                default:
                    throw new FailedException('user not found');
            }

            $this->user[$this->guard] = $user;

            return $user;
        }

        return $user;
    }

    /**
     *
     * @time 2020年01月07日
     * @return mixed
     */
    public function logout()
    {
        switch ($this->getDriver()) {
            case 'jwt':
                return true;
            case 'session':
                Session::delete($this->sessionUserKey());
                return true;
            default:
                throw new FailedException('user not found');
        }
    }

    /**
     *
     * @time 2020年01月07日
     * @param $user
     * @return string
     */
    protected function jwt($user)
    {
        $token = JWTAuth::builder([$this->jwtKey() => $user->id]);

        JWTAuth::setToken($token);

        return $token;
    }

    /**
     *
     * @time 2020年01月07日
     * @param $user
     * @return void
     */
    protected function session($user)
    {
        Session::set($this->sessionUserKey(), $user);
    }

    /**
     *
     * @time 2020年01月07日
     * @return string
     */
    protected function sessionUserKey()
    {
        return $this->guard . '_user';
    }

    /**
     *
     * @time 2020年01月07日
     * @return string
     */
    protected function jwtKey()
    {
        return $this->guard . '_id';
    }

    /**
     *
     * @time 2020年01月07日
     * @return mixed
     */
    protected function getDriver()
    {
        return $this->auth['guards'][$this->guard]['driver'];
    }

    /**
     *
     * @time 2020年01月07日
     * @return mixed
     */
    protected function getProvider()
    {
        if (!isset($this->auth['guards'][$this->guard])) {
            throw new FailedException('Auth Guard Not Found');
        }

        return $this->auth['providers'][$this->auth['guards'][$this->guard]['provider']];
    }

    /**
     *
     * @time 2020年01月07日
     * @param $condition
     * @return mixed
     */
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

    /**
     * 忽略密码认证
     *
     * @time 2021年01月27日
     * @return $this
     */
    public function ignorePasswordVerify(): CatchAuth
    {
        $this->checkPassword = false;

        return $this;
    }
}

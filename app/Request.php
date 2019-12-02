<?php
namespace app;

// 应用请求对象类
use app\exceptions\ValidateFailedException;

class Request extends \think\Request
{
    /**
     * Request constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->validate();
    }

    /**
     * 初始化验证
     *
     * @time 2019年11月27日
     * @throws \Exception
     * @return mixed
     */
    protected function validate()
    {
        if (method_exists($this, 'getValidate')) {
            $validate = $this->getValidate();

            if (!$validate->check(request()->param())) {
                throw new ValidateFailedException($validate->getError());
            }

            return true;
        }
        return true;
    }
}

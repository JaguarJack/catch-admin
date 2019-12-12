<?php
namespace catcher\base;

use app\Request;
use catcher\exceptions\ValidateFailedException;
use think\Validate;

abstract class CatchRequest extends Request
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
        $validate = new Validate();

        if (!$validate->check(request()->param(), $this->rules())) {
            throw new ValidateFailedException($validate->getError());
        }

        return true;
    }

    abstract protected function rules(): array;

    abstract protected function message(): array;
}

<?php
namespace catcher\base;

use app\Request;
use catcher\exceptions\FailedException;
use catcher\exceptions\ValidateFailedException;
use think\Validate;

class CatchRequest extends Request
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
        try {
            $validate = new Validate();

            if (!$validate->check(request()->param(), $this->rules())) {
                throw new FailedException($validate->getError());
            }
        } catch (\Exception $e) {
            throw new ValidateFailedException($e->getMessage());
        }

        return true;
    }
}

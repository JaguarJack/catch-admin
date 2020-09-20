<?php
namespace catcher\base;

use app\Request;
use catcher\exceptions\FailedException;
use catcher\exceptions\ValidateFailedException;

class CatchRequest extends Request
{
  /**
   * @var bool
   */
    protected $needCreatorId = true;
    /**
     *  批量验证
     *
     * @var bool
     */
    protected $batch = false;


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
        if (method_exists($this, 'rules')) {
          try {
            $validate = app('validate');
            // 批量验证
            if ($this->batch) {
              $validate->batch($this->batch);
            }

            // 验证
            $message = $this->message();
            if (!$validate->message(empty($message) ? [] : $message)->check(request()->param(), $this->rules())) {
              throw new FailedException($validate->getError());
            }
          } catch (\Exception $e) {
            throw new ValidateFailedException($e->getMessage());
          }
        }

        // 设置默认参数
        if ($this->needCreatorId) {
            $this->param['creator_id'] = $this->user()->id;
            $this->post['creator_id'] = $this->user()->id;
        }

        return true;
    }
}

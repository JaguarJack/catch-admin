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

            /**
             * // 场景设置验证
             * if (property_exists($this, 'scene') && !empty($this->scene)) {
             * foreach ($this->scene as $scene => $rules) {
             * $validate->scene($scene);
             * // 只限制字段
             * if (!isset($rules['only'])) {
             * $validate->only($rules);
             * } else {
             * $validate->only($rules['only']);
             * // 新增规则
             * if (isset($rules['append'])) {
             * foreach ($rules['append'] as $field => $rule) {
             * $validate->append($field, $rule);
             * }
             * }
             * // 移除规则
             * if (isset($rules['remove'])) {
             * foreach ($rules['remove'] as $field => $rule) {
             * $validate->remove($field, $rule);
             * }
             * }
             * }
             *
             * }
             * }**/

            // 验证
            if (!$validate->check(request()->param(), $this->rules())) {
              throw new FailedException($validate->getError());
            }
          } catch (\Exception $e) {
            throw new ValidateFailedException($e->getMessage());
          }
        }

        // 设置默认参数
        if ($this->needCreatorId) {
          $this->param['creator_id'] = $this->user()->id;
          $this->post['creator'] = $this->user()->id;
        }

        return true;
    }
}

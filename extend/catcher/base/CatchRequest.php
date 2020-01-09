<?php
namespace catcher\base;

use app\Request;
use catcher\CatchAuth;
use catcher\exceptions\FailedException;
use catcher\exceptions\ValidateFailedException;
use think\Validate;

class CatchRequest extends Request
{
    protected $auth;

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
            $validate = new Validate();
            // 批量验证
            if ($this->batch) {
              $validate->batch($this->batch);
            }
            // 自定义规则
            if (method_exists($this, 'newRules')) {
              foreach ($this->newRules() as $rule) {
                $validate->extend($rule->type(), [$rule, 'verify'], $rule->message());
              }
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
        $this->param['creator_id'] = $this->user()->id;

        return true;
    }


  /**
   * login user
   *
   * @time 2020年01月09日
   * @return mixed
   */
    public function user()
    {
      if (!$this->auth) {
        $this->auth = new CatchAuth;
      }

      return $this->auth->user();
    }
}

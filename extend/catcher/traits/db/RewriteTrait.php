<?php
namespace catcher\traits\db;

/**
 * 重写 think\Model 的方法
 *
 * Trait RewriteTrait
 * @package catcher\traits\db
 */
trait RewriteTrait
{
    /**
     * 初始化
     *
     * CatchModel constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->hidden = array_merge($this->hidden, $this->defaultHiddenFields());
    }

    /**
     * hidden model fields
     *
     * @return array
     */
    protected function defaultHiddenFields(): array
    {
        return [$this->deleteTime];
    }

    /**
     * 重写 hidden 方法，支持合并 hidden 属性
     *
     * @param array $hidden
     * @return $this
     */
    public function hidden(array $hidden = [])
    {
        /**
         * 合并属性
         */
        if (!count($this->hidden)) {
            $this->hidden = array_merge($this->hidden, $hidden);

            return $this;
        }

        $this->hidden = $hidden;

        return $this;
    }
}
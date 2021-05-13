<?php
namespace catcher\library\table;

trait ComponentsTrait
{
    /**
     * component
     *
     * @time 2021年03月24日
     * @param string $name
     * @param string $updateField
     * @param array $options
     * @return ComponentsTrait|HeaderItem
     */
    public function component(string $name, string $updateField = '', $options = [])
    {
        $this->attributes['component'][] = [
            'name' => $name,
            'field' => $updateField ? : $this->attributes['prop'],
            'options' => $options
        ];

        return $this;
    }

    /**
     * switch
     *
     * @time 2021年03月23日
     * @param null $updateFields
     * @return HeaderItem
     */
    public function withSwitchComponent(array $options = [], $updateFields = null): HeaderItem
    {
        return $this->component('switch_', $updateFields ? : $this->attributes['prop'], $options);
    }

    /**
     * edit
     *
     * @time 2021年03月23日
     * @param null $updateFields
     * @return HeaderItem
     */
    public function withEditComponent($updateFields = null): HeaderItem
    {
        return $this->component('edit', $updateFields ? : $this->attributes['prop']);
    }

    /**
     * Edit Number
     *
     * @time 2021年03月23日
     * @param null $updateFields
     * @return HeaderItem
     */
    public function withEditNumberComponent($updateFields = null): HeaderItem
    {
        return $this->component('editNumber', $updateFields ? : $this->attributes['prop']);
    }


    /**
     * 多选组件
     *
     * @time 2021年05月03日
     * @param array $options
     * @param null $updateFields
     * @return HeaderItem
     */
    public function withSelectComponent(array $options, $updateFields = null): HeaderItem
    {
        return $this->component('select_', $updateFields ? : $this->attributes['prop'], $options);
    }

    /**
     * 预览组件
     *
     * @time 2021年05月03日
     * @param null $field
     * @return ComponentsTrait|HeaderItem
     */
    public function withPreviewComponent($field = null)
    {
        return $this->component('preview', $field ? : $this->attributes['prop']);
    }

    /**
     * 链接跳转
     *
     * @time 2021年05月09日
     * @param null $field
     * @return ComponentsTrait|HeaderItem
     */
    public function withUrlComponent($field = null)
    {
        return $this->component('url', $field ? : $this->attributes['prop']);
    }

    /**
     * 复制组件
     *
     * @time 2021年05月12日
     * @param null $field
     * @return ComponentsTrait|HeaderItem
     */
    public function withCopyComponent($field = null)
    {
        return $this->component('copy', $field ? : $this->attributes['prop']);
    }

    /**
     * download 组件
     *
     * @time 2021年05月05日
     * @param null $field
     * @return ComponentsTrait|HeaderItem
     */
    public function withDownloadComponent($field = null)
    {
        return $this->component('download', $field ? : $this->attributes['prop']);
    }
}

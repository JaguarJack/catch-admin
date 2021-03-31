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
    public function withSwitchComponent($updateFields = null): HeaderItem
    {
        return $this->component('switch_', $updateFields ? : $this->attributes['prop']);
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


    public function withSelectComponent(array $options, $updateFields = null): HeaderItem
    {
        return $this->component('select_', $updateFields ? : $this->attributes['prop'], $options);
    }
}
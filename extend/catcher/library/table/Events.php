<?php
namespace catcher\library\table;

trait Events
{
    /**
     * 表格选择事件
     *
     * @time 2021年03月29日
     * @return mixed
     */
    public function selectionChange()
    {
        $this->appendEvents([
            'selection-change' => 'handleSelectMulti'
        ]);
        
        return $this;
    }
}
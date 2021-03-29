<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catcher\library\form;


class FormOptions
{
    protected $options = [];

    /**
     * 增加 option
     *
     * @time 2021年03月24日
     * @param $label
     * @param $value
     * @return $this
     */
    public function add($label, $value): FormOptions
    {
        $this->options[] = [
            'value' => $value,
            'label' => $label,
        ];

        return $this;
    }

    /**
     * 获取
     *
     * @time 2021年03月24日
     * @return array
     */
    public function render()
    {
        return $this->options;
    }
}

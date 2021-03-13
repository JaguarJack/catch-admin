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


trait FormOptions
{
    /**
     * 是/否的选项
     *
     * @time 2021年03月06日
     * @param array $label
     * @param array $value
     * @return array[]
     */
    protected function yesOrNo(array $label = [], array $value = []): array
    {
        if (!count($label)) {
            $label = ['是', '否'];
        }

        if (!count($value)) {
            $value = [1, 2];
        }

        return [
            ['value' => $value[0], 'label' => $label[0]],
            ['value' => $value[1], 'label' => $label[1]],
        ];
    }
}

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

namespace catchAdmin\cms\support;

use catcher\Utils;

class Helper
{
    /**
     * 获取数组格式 options
     *
     * @time 2021年03月09日
     * @param $value
     * @return mixed
     */
    public static function getOptions(string $value)
    {
        $options = [];

        if (!$value) {
            return $value;
        }

        foreach (Utils::stringToArrayBy($value, PHP_EOL) as $option) {
            if ($option) {
                $option = explode('|', $option);

                $options[] = [
                    'value' => $option[0],
                    'label' => $option[1],
                ];
            }
        }

        return $options;
    }

    /**
     * 处理表单数组字符
     *
     *  "[1, 2, 3, 4, 5]"
     *
     * @time 2021年03月07日
     * @param $arrayString
     * @return array|string[]
     */
    public static function dealWithFormArrayString($arrayString): array
    {
        $array = trim(trim($arrayString, '['), ']');

        if (!$array) {
            return [];
        }

        return Utils::stringToArrayBy($array);
    }
}

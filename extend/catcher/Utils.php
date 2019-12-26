<?php
namespace catcher;

use think\helper\Str;

class Utils
{
  /**
   * 字符串转换成数组
   *
   * @time 2019年12月25日
   * @param string $string
   * @param string $dep
   * @return array
   */
    public static function stringToArrayBy(string  $string, $dep = ','): array
    {
        if (Str::contains($string, $dep)) {
            return explode($dep, trim($string, $dep));
        }

        return [$string];
    }
}

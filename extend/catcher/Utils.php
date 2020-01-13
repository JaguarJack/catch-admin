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

  /**
   * 搜索参数
   *
   * @time 2020年01月13日
   * @param array $params
   * @param array $range
   * @return array
   */
    public static function filterSearchParams(array $params, array $range = []): array
    {
        $search = [];

        // $range = array_merge(['created_at' => ['start_at', 'end_at']], $range);

        if (!empty($range)) {
          foreach ($range as $field => $rangeField) {
            if (count($rangeField) === 1) {
              $search[$field] = [$params[$rangeField[0]]];
              unset($params[$rangeField[0]]);
            } else {
              $search[$field] = [$params[$rangeField[0]], $params[$rangeField[1]]];
              unset($params[$rangeField[0]], $params[$rangeField[1]]);
            }
          }
        }

        return array_merge($search, $params);

    }
}

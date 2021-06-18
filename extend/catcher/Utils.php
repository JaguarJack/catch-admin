<?php
declare(strict_types=1);

namespace catcher;

use catchAdmin\system\model\Config;
use think\facade\Cache;
use think\facade\Db;
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

    /**
     * 导入树形数据
     *
     * @time 2020年04月29日
     * @param $data
     * @param $table
     * @param string $pid
     * @param string $primaryKey
     * @return void
     */
    public static function importTreeData($data, $table, string $pid = 'parent_id', string $primaryKey = 'id')
    {
        foreach ($data as $value) {
            if (isset($value[$primaryKey])) {
                unset($value[$primaryKey]);
            }

            $children = $value['children'] ?? false;
            if($children) {
                unset($value['children']);
            }

            // 首先查询是否存在
            $menu = Db::name($table)
                        ->where('permission_name', $value['permission_name'])
                        ->where('module', $value['module'])
                        ->where('permission_mark', $value['permission_mark'])
                        ->find();

            if (!empty($menu)) {
                $id = $menu['id'];
            } else {
                $id = Db::name($table)->insertGetId($value);
            }
            if ($children) {
                foreach ($children as &$v) {
                    $v[$pid] = $id;
                    $v['level'] = !$value[$pid] ? $id : $value['level'] . '-' .$id;
                }
                self::importTreeData($children, $table, $pid);
            }
        }
    }

    /**
     *  解析 Rule 规则
     *
     * @time 2020年05月06日
     * @param $rule
     * @return array
     */
    public static function parseRule($rule): array
    {
        [$controller, $action] = explode(Str::contains($rule, '@') ? '@' : '/', $rule);

        $controller = explode('\\', $controller);

        $controllerName = lcfirst(array_pop($controller));

        array_pop($controller);

        $module = array_pop($controller);

        return [$module, $controllerName, $action];
    }


    /**
     * get controller & action
     *
     * @time 2020年10月12日
     * @param $rule
     * @return false|string[]
     * @throws \ReflectionException
     */
    public static function isMethodNeedAuth($rule)
    {
        list($controller, $action) = explode(Str::contains($rule, '@') ? '@' : '/', $rule);

        $docComment = (new \ReflectionClass($controller))->getMethod($action)->getDocComment();

        if (! $docComment) {
            return false;
        }

        return strpos($docComment, config('catch.permissions.method_auth_mark')) !== false;
    }


    /**
     * 表前缀
     *
     * @time 2020年05月22日
     * @return mixed
     */
    public static function tablePrefix()
    {
        return \config('database.connections.mysql.prefix');
    }

    /**
     * 删除表前缀
     *
     * @time 2020年12月01日
     * @param string $table
     * @return string|string[]
     */
    public static function tableWithoutPrefix(string $table)
    {
        return str_replace(self::tablePrefix(), '', $table);
    }

    /**
     * 添加表前缀
     *
     * @time 2020年12月26日
     * @param string $table
     * @return string
     */
    public static function tableWithPrefix(string $table): string
    {
        return Str::contains($table, self::tablePrefix()) ?
                    $table : self::tablePrefix() . $table;
    }

    /**
     * 是否是超级管理员
     *
     * @time 2020年07月04日
     * @return bool
     */
    public static function isSuperAdmin(): bool
    {
        return request()->user()->id == config('catch.permissions.super_admin_id');
    }

    /**
     * 获取配置
     *
     * @time 2020年09月07日
     * @param $key
     * @return mixed
     */
    public static function config($key)
    {
        return Config::where('key', $key)->value('value');
    }

    /**
     * public path
     *
     * @param string $path
     * @time 2020年09月08日
     * @return string
     */
    public static function publicPath(string $path = ''): string
    {
        return root_path($path ? 'public/'. $path : 'public');
    }


    /**
     * 过滤空字符字段
     *
     * @time 2021年01月16日
     * @param $data
     * @return mixed
     */
    public static function filterEmptyValue($data)
    {
        foreach ($data as $k => $v) {
            if (!$v) {
                unset($data[$k]);
            }
        }

        return $data;
    }

    /**
     * 设置 filesystem config
     *
     * @time 2021年03月01日
     * @return void
     */
    public static function setFilesystemConfig()
    {
        $configModel = app(Config::class);

        $upload = $configModel->where('key', 'upload')->find();

        if ($upload) {
            $disk = app()->config->get('filesystem.disks');

            $uploadConfigs = $configModel->getConfig($upload->component);

            if (!empty($uploadConfigs)) {
                // 读取上传可配置数据
                foreach ($uploadConfigs as $key => &$config) {
                    // $disk[$key]['type'] = $key;
                    // 腾讯云配置处理
                    if (strtolower($key) == 'qcloud') {
                        $config['credentials'] = [
                            'appId' => $config['app_id'] ?? '',
                            'secretKey' => $config['secret_key'] ?? '',
                            'secretId' => $config['secret_id'] ?? '',
                        ];
                        $readFromCdn = $config['read_from_cdn'] ?? 0;
                        $config['read_from_cdn'] = intval($readFromCdn) == 1;
                    }
                    // OSS 配置
                    if (strtolower($key) == 'oss') {
                        $isCname = $config['is_cname'] ?? 0;
                        $config['is_cname'] = intval($isCname) == 1;
                    }
                }

                // 合并数组
                array_walk($disk, function (&$item, $key) use ($uploadConfigs) {
                    if (!in_array($key, ['public', 'local'])) {
                        if ($uploadConfigs[$key] ?? false) {
                            foreach ($uploadConfigs[$key] as $k => $value) {
                                $item[$k] = $value;
                            }
                        }
                    }
                });


                $default = Utils::config('site.upload');
                // 重新分配配置
                app()->config->set([
                    'default' =>  $default ? : 'local',
                    'disks' => $disk,
                ], 'filesystem');
            }
        }
    }

    /**
     * 缓存操作
     *
     * @time 2021年06月18日
     * @param string $key
     * @param \Closure $callable
     * @param int $ttl
     * @param string $store
     * @return mixed
     *@throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function cache(string $key, \Closure $callable, int $ttl = 0, string $store = 'redis')
    {
        if (Cache::store($store)->has($key)) {
            return Cache::store($store)->get($store);
        }

        $cache = $callable();

        Cache::store($store)->set($key, $cache, $ttl);

        return $cache;
    }
}

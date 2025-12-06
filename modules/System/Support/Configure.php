<?php

namespace Modules\System\Support;

use Illuminate\Support\Str;
use Modules\System\Models\SystemConfig;

/**
 * 配置类
 *
 * 解析/缓存/获取 system_config 对应数据
 */
class Configure
{
    /**
     * 缓存系统配置 KEY
     */
    protected string $systemConfigKey = 'system_config';

    /**
     * 解析配置参数
     */
    public static function parse(string $prefix, array $params): array
    {
        $config = [];
        foreach ($params as $k => $value) {
            if ($value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $config["$prefix.$k"] = $value;
            }

            if (is_numeric($value)) {
                $config["$prefix.$k"] = $value;
            }
        }

        return $config;
    }

    /**
     * 缓存配置
     */
    public function cache(): void
    {
        $config = [];

        SystemConfig::query()
            ->get()
            ->each(function (SystemConfig $systemConfig) use (&$config) {
                $keys = Str::of($systemConfig->key)->explode('.');
                // 数字需要强转，由于入库是字符串，所以返回的时候最好是对应 float/int 类型
                if (is_numeric($systemConfig->value)) {
                    $systemConfig->value = $this->parseNumber($systemConfig->value);
                } else {
                    // 如果是 json
                    if ($this->isJsonValidate($systemConfig->value)) {
                        $systemConfig->value = json_decode($systemConfig->value, true);
                    }
                }

                if (count($keys) == 2) {
                    [$k1, $k2] = $keys;
                    $config[$k1][$k2] = $systemConfig->value;
                }

                if (count($keys) == 3) {
                    [$k1, $k2, $k3] = $keys;
                    $config[$k1][$k2][$k3] = $systemConfig->value;
                }

                if (count($keys) == 4) {
                    [$k1, $k2, $k3, $k4] = $keys;
                    $config[$k1][$k2][$k3][$k4] = $systemConfig->value;
                }
            });

        admin_cache_delete($this->systemConfigKey);
        admin_cache($this->systemConfigKey, null, function () use ($config) {
            return $config;
        });
    }

    /**
     * load
     *
     * @param  null  $callback
     */
    public function loadToLaravelConfig($config, $callback = null): void
    {
        $systemConfig = admin_cache_get($this->systemConfigKey, []);

        if (! empty($systemConfig) && is_array($systemConfig)) {
            foreach ($systemConfig as $k => $value) {
                $config->set($k, $value);
            }

            if ($callback) {
                $callback($this->systemConfigKey);
            }
        }
    }

    /**
     * 清理系统文件配置
     *
     * @return bool
     */
    public function clear(): bool
    {
        return admin_cache_delete($this->systemConfigKey);
    }

    /**
     * 解析 number
     */
    protected function parseNumber(mixed $number): float|int
    {
        return str_contains($number, '.') ? (float) $number : (int) $number;
    }

    /**
     * json 校验
     */
    protected function isJsonValidate(string $json): bool
    {
        json_decode($json);

        return json_last_error() === JSON_ERROR_NONE;
    }
}

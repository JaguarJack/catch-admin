<?php
declare(strict_types=1);

namespace catcher\facade;

use think\Facade;

/**
 * @method static \catcher\library\client\Http headers(array $headers)
 * @method static \catcher\library\client\Http body($body)
 * @method static \catcher\library\client\Http json(array $data)
 * @method static \catcher\library\client\Http query(array $query)
 * @method static \catcher\library\client\Http form(array $params)
 * @method static \catcher\library\client\Http timeout($timeout)
 * @method static \catcher\library\client\Http get(string $url)
 * @method static \catcher\library\client\Http post(string $url)
 * @method static \catcher\library\client\Http put(string $url)
 * @method static \catcher\library\client\Http delete(string $url)
 * @method static \catcher\library\client\Http token(string $token)
 * @method static \catcher\library\client\Http ignoreSsl()
 * @method static \catcher\library\client\Http attach($name, $resource, $filename)
 *
 * @time 2020年05月22日
 */
class Http extends Facade
{
    protected static $alwaysNewInstance = true;

    protected static function getFacadeClass()
    {
        return \catcher\library\client\Http::class;
    }
}

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

use catcher\generate\support\Table as _Table;

/**
 * @method static create(string $primaryKey, string $engine, string $comment)
 * @method static exist($tableName)
 * @method static drop($tableName)
 * @method static addColumn($tableName, $column)
 * @method static hasColumn($tableName, string $column)
 * @method static columns($tableName)
 * @method static dropColumn($tableName, string $column)
 * @method static addUniqueIndex($tableName, string $column)
 * @method static addIndex($tableName, string $column)
 * @method static addFulltextIndex($tableName, string $column)
 * @method static dropIndex($tableName, string $column)
 * @method static isIndex($tableName, string $column)
 *
 * @time 2021年04月30日
 */
class Table
{
    /**
     * 静态访问
     *
     * @time 2021年04月30日
     * @param $method
     * @param $params
     * @return false|mixed
     */
    public static function __callStatic($method, $params)
    {
        $table = new _Table($params[0]);

        unset($params[0]);

        return call_user_func_array([$table, $method], $params);
    }
}

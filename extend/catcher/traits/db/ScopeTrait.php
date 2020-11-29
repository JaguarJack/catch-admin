<?php
declare(strict_types=1);

/**
 * @filename  ScopeTrait.php
 * @createdAt 2020/6/21
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catcher\traits\db;

use catchAdmin\permissions\model\Users;

trait ScopeTrait
{
    /**
     * 创建人
     *
     * @time 2020年06月17日
     * @param $query
     * @return mixed
     */
    public function scopeCreator($query)
    {
        if (property_exists($this, 'field') && in_array('creator_id', $this->field)) {
            return $query->addSelectSub(function () {
                $user = app(Users::class);
                return $user->whereColumn($this->getTable() . '.creator_id', $user->getTable() . '.id')
                    ->field('username');
            }, 'creator');
        }

        return $query;
    }
}
<?php

namespace Modules\Common\Support;

use Modules\Permissions\Models\Permissions;

class ImportPermissions
{
    public static function import(array $data, string $pid = 'parent_id', string $primaryKey = 'id'): void
    {
        foreach ($data as $value) {
            if (isset($value[$primaryKey])) {
                unset($value[$primaryKey]);
            }

            $children = $value['children'] ?? false;
            if ($children) {
                unset($value['children']);
            }

            $id = Permissions::query()
                                ->where('permission_name', $value['permission_name'])
                                ->where('module', $value['module'])
                                ->where('permission_mark', $value['permission_mark'])
                                ->value('id');

            if (!$id) {
                $id = app(Permissions::class)->createBy($value);
            }
            if ($children) {
                foreach ($children as &$v) {
                    $v[$pid] = $id;
                }
                unset($v);
                self::import($children, $pid);
            }
        }
    }

}

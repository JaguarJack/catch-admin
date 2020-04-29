<?php
namespace catchAdmin\permissions\model\search;

use catchAdmin\permissions\model\Roles;

trait PermissionsSearch
{
    public function searchPermissionNameAttr($query, $value, $data)
    {
        return $query->whereLike('permission_name', $value);
    }

    public function searchIdAttr($query, $value, $data)
    {
      $query->where('parent_id', $value)->whereOr('id', $value);
    }

    public function searchRoleIdAttr($query, $value, $data)
    {
        $permissionIds = [];
        $permissions = Roles::where('id', $value)->find()->getPermissions();

        foreach ($permissions as $_permission) {
          $permissionIds[] = $_permission->pivot->permission_id;
        }
        
        if(!empty($permissionIds)) {
          $query->whereIn('id', $permissionIds);
        }
    }
}

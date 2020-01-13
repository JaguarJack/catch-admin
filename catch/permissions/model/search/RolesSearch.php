<?php
namespace catchAdmin\permissions\model\search;

trait RolesSearch
{
  public function searchRoleNameAttr($query, $value, $data)
  {
    return $query->whereLike('role_name', $value);
  }

  public function searchIdAttr($query, $value, $data)
  {
    $query->where('parent_id', $value)->whereOr('id', $value);
  }

}

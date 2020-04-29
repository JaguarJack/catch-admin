<?php
namespace catchAdmin\permissions\model\search;

trait JobsSearch
{
  public function searchJobNameAttr($query, $value, $data)
  {
    return $query->whereLike('job_name', $value);
  }

  public function searchCodingAttr($query, $value, $data)
  {
    return $query->whereLike('coding', $value);
  }

  public function searchStatusAttr($query, $value, $data)
  {
    return $query->where('status', $value);
  }
}

<?php
namespace catcher;

use think\db\Query;

class CatchQuery extends Query
{
  /**
   *
   * @time 2020年01月13日
   * @param string $model
   * @param string $joinField
   * @param string $currentJoinField
   * @param string $type
   * @param array $bind
   * @return CatchQuery
   */
    public function catchJoin(string $model, string $joinField, string $currentJoinField, string $type = 'INNER', array $bind = [])
    {
      $table = app($model)->getTable();

      return $this->join($table, sprintf('%s.%s=%s.%s', $table, $joinField, $this->getTable(), $currentJoinField), $type, $bind);
    }

  /**
   *
   * @time 2020年01月13日
   * @param string $model
   * @param string $joinField
   * @param string $currentJoinField
   * @param array $bind
   * @return CatchQuery
   */
    public function catchLeftJoin(string $model, string $joinField, string $currentJoinField, array $bind = [])
    {
      return $this->catchJoin($model, $joinField,  $currentJoinField, 'LEFT', $bind);
    }

  /**
   * 
   * @time 2020年01月13日
   * @param string $model
   * @param string $joinField
   * @param string $currentJoinField
   * @param array $bind
   * @return CatchQuery
   */
    public function catchRightJoin(string $model, string $joinField, string $currentJoinField, array $bind = [])
    {
      return $this->catchJoin($model, $joinField,  $currentJoinField, 'RIGHT', $bind);
    }
}

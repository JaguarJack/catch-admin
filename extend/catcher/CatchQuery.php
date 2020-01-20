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
   * @param array $field
   * @param string $type
   * @param array $bind
   * @return CatchQuery
   */
    public function catchJoin(string $model, string $joinField, string $currentJoinField, array $field = [], string $type = 'INNER', array $bind = []): CatchQuery
    {
        $table = app($model)->getTable();

        // 合并字段
        $this->options['field'] = array_merge($this->options['field'], array_map(function ($value) use ($table) {
          return $table . '.' . $value;
        }, $field));

        return $this->join($table, sprintf('%s.%s=%s.%s', $table, $joinField, $this->getAlias(), $currentJoinField), $type, $bind);
    }

  /**
   *
   * @time 2020年01月13日
   * @param string $model
   * @param string $joinField
   * @param string $currentJoinField
   * @param array $field
   * @param array $bind
   * @return CatchQuery
   */
    public function catchLeftJoin(string $model, string $joinField, string $currentJoinField, array $field = [], array $bind = []): CatchQuery
    {
        return $this->catchJoin($model, $joinField,  $currentJoinField,  $field,'LEFT', $bind);
    }

  /**
   *
   * @time 2020年01月13日
   * @param string $model
   * @param string $joinField
   * @param string $currentJoinField
   * @param array $field
   * @param array $bind
   * @return CatchQuery
   */
    public function catchRightJoin(string $model, string $joinField, string $currentJoinField, array $field = [], array $bind = []): CatchQuery
    {
        return $this->catchJoin($model, $joinField,  $currentJoinField, $field,'RIGHT', $bind);
    }

  /**
   * rewrite
   *
   * @time 2020年01月13日
   * @param array|string $field
   * @param bool $needAlias
   * @return $this|Query
   */
    public function withoutField($field, $needAlias = false)
    {
      if (empty($field)) {
        return $this;
      }

      if (is_string($field)) {
        $field = array_map('trim', explode(',', $field));
      }

      // 过滤软删除字段
      $field[] = $this->model->getDeleteAtField();

      // 字段排除
      $fields = $this->getTableFields();
      $field  = $fields ? array_diff($fields, $field) : $field;

      if (isset($this->options['field'])) {
        $field = array_merge((array) $this->options['field'], $field);
      }

      $this->options['field'] = array_unique($field);

      if ($needAlias) {
        $alias = $this->getAlias();

        $this->options['field'] = array_map(function ($field) use ($alias) {
          return $alias . '.' . $field;
        }, $this->options['field']);
      }

      return $this;
    }

  /**
   *
   * @time 2020年01月13日
   * @return CatchQuery
   */
    public function catchSearch(): CatchQuery
    {
        $params = \request()->param();

        return $this->withSearch(array_keys($params), Utils::filterSearchParams($params));
    }

  /**
   *
   * @time 2020年01月13日
   * @return mixed
   */
    public function getAlias()
    {
      return isset($this->options['alias']) ? $this->options['alias'][$this->getTable()] : $this->getTable();
    }

  /**
   * rewrite
   *
   * @time 2020年01月13日
   * @param string $field
   * @param mixed $condition
   * @param string $option
   * @param string $logic
   * @return Query
   */
    public function whereLike(string $field, $condition, string $logic = 'AND', $option = 'both'): Query
    {
        switch ($option) {
          case 'both':
              $condition = '%' . $condition . '%';
              break;
          case 'left':
              $condition = '%' . $condition;
              break;
          default:
              $condition .= '%';
        }

        return parent::whereLike($this->getAlias() . '.' . $field, $condition, $logic);
    }

  /**
   * 额外的字段
   *
   * @time 2020年01月13日
   * @param array $fields
   * @return CatchQuery
   */
    public function addFields(array $fields): CatchQuery
    {
        $this->options['field'] = array_merge($this->options['field'], $fields);

        return $this;
    }
}

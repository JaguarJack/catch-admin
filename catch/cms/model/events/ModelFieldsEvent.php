<?php
namespace catchAdmin\cms\model\events;

use catchAdmin\cms\exceptions\ColumnException;
use catchAdmin\cms\model\Models;
use catchAdmin\cms\support\Table;
use catchAdmin\cms\support\TableColumn;
use catchAdmin\cms\tables\Model;
use catcher\exceptions\FailedException;

trait ModelFieldsEvent
{
    protected static $modelTableName = null;

    /**
     * 插入前
     *
     * @time 2021年03月08日
     * @param $modelFields
     * @return void
     */
    public static function onBeforeInsert($modelFields)
    {
        $tableName =  self::getModelTableName($modelFields->getData('model_id'));

        if ($tableName && Table::hasColumn($tableName, $modelFields->getData('name'))) {
            throw new ColumnException(sprintf('Column [%s] already exist in Table [%s]', $modelFields->getData('name'), $tableName));
        }

        $modelFields->data(self::changeData($modelFields->getData()));
    }

    /**
     * 更新后
     *
     * @time 2021年03月08日
     * @param $modelFields
     * @return void
     */
    public static function onBeforeUpdate($modelFields)
    {
        $modelFields->data(self::changeData($modelFields->getData(), true));
    }

    /**
     * 插入之后
     *
     * @time 2021年03月08日
     * @param $modelFields
     * @return void
     */
    public static function onAfterInsert($modelFields)
    {
        if ($modelFields->getKey()) {
           try {
               $tableName =  self::getModelTableName($modelFields->getData('model_id'));

               if ($tableName) {
                  Table::addColumn($tableName, (new TableColumn($modelFields->getData()))->get());
               }

               self::addIndexForField($tableName, $modelFields->getData('name'), $modelFields->getData('is_index'));
           } catch (\Exception $e) {
               $modelFields->delete();
               throw new FailedException($e->getMessage());
           }
        }
    }

    /**
     * 更新之后
     *
     * @time 2021年04月30日
     * @param \think\Model $modelFields
     * @return void
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public static function onAfterUpdate(\think\Model $modelFields)
    {
        $field =  $modelFields->find($modelFields->getWhere());

        self::addIndexForField(self::getModelTableName($field['model_id']), $field['name'], $field['is_index']);
    }


    /**
     * 添加索引
     *
     * @time 2021年04月30日
     * @param $table
     * @param string $column
     * @param $isIndex
     * @return void
     */
    protected static function addIndexForField($table, string $column, $isIndex)
    {
        if (! Table::isIndex($table, $column) && $isIndex == self::IS_INDEX) {
            Table::addIndex($table, $column);
        }

        if (Table::isIndex($table, $column) && $isIndex == self::NOT_INDEX) {
            Table::dropIndex($table, $column);
        }
    }

    /**
     * 删除字段
     *
     * @time 2021年03月08日
     * @param $modelFields
     * @return void
     */
    public static function onAfterDelete($modelFields)
    {
        $tableName = self::getModelTableName($modelFields->getData('model_id'));

        $columnName = $modelFields->getData('name');

        if (Table::hasColumn($tableName, $columnName)) {
            Table::dropColumn($tableName, $columnName);
        }
    }

    /**
     * 校验
     *
     * @time 2021年03月08日
     * @param $data
     * @param bool $update
     * @return mixed
     */
    protected static function changeData($data, $update = false)
    {
        if (isset($data['type']) && !in_array($data['type'], ['string', 'int'])) {
            $data['length'] = 0;
        }

        // 更新不会校验
        if (!$update) {
            if (Table::hasColumn(self::getModelTableName($data['model_id']), $data['name'])) {
                throw new ColumnException(sprintf('Column [%s] already exist in Table [%s]', $data['name'], self::getModelTableName($data['model_id'])));
            }
        }

        return $data;
    }

    /**
     * 获取模型关联的表
     *
     * @time 2021年03月08日
     * @param $modelId
     * @return mixed|null
     */
    protected static function getModelTableName($modelId)
    {
        if (self::$modelTableName) {
            return self::$modelTableName;
        }

        self::$modelTableName = Models::where('id', $modelId)->value('table_name');

        return self::$modelTableName;
    }
}
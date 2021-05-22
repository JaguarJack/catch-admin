<?php
namespace catchAdmin\cms\support;

use catchAdmin\cms\model\ModelAuxiliaryTable;
use catchAdmin\cms\model\Models;
use catcher\exceptions\FailedException;

class AuxiliaryTable
{
    protected $suffixes = [
        'first', 'second', 'third', 'fourth', 'fifth',
        'sixth', 'seventh', 'eighth', 'ninth', 'tenth'
    ];

    protected $mainTableId = 'main_id';

    protected $auxiliaryTableName = null;

    /**
     * 创建副表
     *
     * @time 2021年03月08日
     * @param int $modelId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return string
     */
    public  function create(int $modelId): string
    {
        try {
            // 查询主表名称
            $tableName = Models::where('id', $modelId)->value('table_name');
            // 查询副表
            $auxiliaryTables = ModelAuxiliaryTable::where('model_id', $modelId)->select();
            // 目前最多允许创建 10 个副表
            if ($auxiliaryTables->count() > count($this->suffixes)) {
                throw new FailedException('最多只允许创建 ' . count($this->suffixes) . ' 个副表');
            }
            $defaultUsed = ModelAuxiliaryTable::NOT_USE;
            // 如果模型还没有关联的副表
            // 默认创建副表
            if (!$auxiliaryTables->count()) {
                $defaultUsed = ModelAuxiliaryTable::USED;

                $this->auxiliaryTableName = $this->getName($tableName, array_shift($this->suffixes));
            } else {
                $existSuffixes = [];

                $auxiliaryTables->each(function ($table) use (&$existSuffixes) {
                    $name = explode('_', $table->name);

                    $existSuffixes[] = array_pop($name);
                });

                $notUsed = array_diff($this->suffixes, $existSuffixes);

                $this->auxiliaryTableName = $this->getName($tableName, array_shift($notUsed));
            }

            if (Table::create($this->auxiliaryTableName)) {
                Table::addColumn($this->auxiliaryTableName, (new TableColumn([
                    'type' => 'int',
                    'name' => $this->mainTableId,
                    'length' => 10,
                    'title' => '主表数据的ID',
                    'default_value' => 0,
                ]))->get());
            }

            app(ModelAuxiliaryTable::class)->storeBy([
                'model_id' => $modelId,
                'table_name' => $this->auxiliaryTableName,
                'used' => $defaultUsed,
            ]);

            return $this->auxiliaryTableName;
        } catch (\Exception $exception) {
            throw new FailedException($exception->getMessage());
        }
    }

    /**
     * 获取默认使用的副表
     *
     * @time 2021年03月08日
     * @param int $modelId
     * @return mixed
     */
    public function getUsedAuxiliaryTable(int $modelId)
    {
       $auxiliaryTable = app(ModelAuxiliaryTable::class)->getUsed($modelId);

       return $auxiliaryTable ? $auxiliaryTable->table_name : null;
    }


    /**
     * 获取副表名称
     *
     * @time 2021年03月08日
     * @param string $mainTable
     * @param string $suffix
     * @return string
     */
    protected function getName(string $mainTable, string $suffix): string
    {
        return $mainTable . '_relate_' . $suffix;
    }



}
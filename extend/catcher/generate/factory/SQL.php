<?php
namespace catcher\generate\factory;

use catcher\exceptions\FailedException;
use catcher\generate\support\Table;
use catcher\generate\support\TableColumn;
use catcher\generate\TableExistException;
use Phinx\Db\Adapter\AdapterInterface;

class SQL extends Factory
{
    public function done(array $params)
    {
        if (!$params['table'] ?? false) {
            throw new FailedException('table name has lost~');
        }

        $this->createTable($params);

        $this->createTableColumns($params['sql'], $params['extra']);

        $this->createTableIndex($this->getIndexColumns($params['sql']));

        return $params['table'];
    }

    /**
     * 创建表
     *
     * @time 2021年03月13日
     * @param array $params
     * @return void
     */
    protected function createTable(array $params)
    {
       $table = new Table($params['table']);

       if ($table::exist()) {
           throw new TableExistException(sprintf('Table [%s] has been existed', $params['table']));
       }

       if(!$table::create(
            $params['extra']['primary_key'],
            $params['extra']['engine'],
            $params['extra']['comment']
        )) {
           throw new FailedException(sprintf('created table [%s] failed', $params['table']));
       }
    }

    /**
     * 创建 columns
     *
     * @time 2021年03月13日
     * @param $columns
     * @param $extra
     * @return void
     */
    protected function createTableColumns($columns, $extra)
    {
        $tableColumns = [];

        foreach ($columns as $column) {
            if ($column['type'] === AdapterInterface::PHINX_TYPE_DECIMAL) {
                $tableColumn = (new TableColumn)->{$column['type']}($column['field']);
            } else if ($column['type'] === AdapterInterface::PHINX_TYPE_ENUM || $column['type'] === AdapterInterface::PHINX_TYPE_SET) {
                $tableColumn = (new TableColumn)->{$column['type']}($column['field'], $column['default']);
            }else {
                $tableColumn = (new TableColumn)->{$column['type']}($column['field'], $column['length'] ?? 0);
            }

            if ($column['nullable']) {
                $tableColumn->setNullable();
            }

            if ($column['unsigned']) {
                $tableColumn->setUnsigned();
            }


            if ($column['comment']) {
                $tableColumn->setComment($column['comment']);
            }

            if (!$this->doNotNeedDefaultValueType($column['type'])) {
                $tableColumn->setDefault($column['default']);
            }

            $tableColumns[] = $tableColumn;
        }


        if ($extra['created_at']) {
            $tableColumns[] = $this->createCreateAtColumn();
            $tableColumns[] = $this->createUpdateAtColumn();
        }

        if ($extra['soft_delete']) {
            $tableColumns[] = $this->createDeleteAtColumn();
        }

        if ($extra['creator_id']) {
            $tableColumns[] = $this->createCreatorIdColumn();
        }

        foreach ($tableColumns as $column) {
            if (!Table::addColumn($column)) {
                throw new FailedException('创建失败');
            }
        }
    }

    /**
     * 创建 index
     *
     * @time 2021年03月13日
     * @param $indexes
     * @return void
     */
    protected function createTableIndex($indexes)
    {
        $method = [
            'index' => 'addIndex',
            'unique' => 'addUniqueIndex',
            'fulltext' => 'addFulltextIndex',
        ];

        foreach ($indexes as $type => $index) {
            foreach ($index as $i) {
                Table::{$method[$type]}($i);
            }
        }
    }

    /**
     * 获取有索引的 column
     *
     * @time 2021年03月13日
     * @param $columns
     * @return array
     */
    protected function getIndexColumns($columns): array
    {
        $index = [];

        foreach ($columns as $column) {
            if ($column['index']) {
                $index[$column['index']][] = $column['field'];
            }
        }

        return $index;
    }

    /**
     * 不需要默认值
     *
     * @param string $type
     * @time 2020年10月23日
     * @return  bool
     */
    protected function doNotNeedDefaultValueType(string $type): bool
    {
        return in_array($type, [
            'blob', 'text', 'geometry', 'json',
            'tinytext', 'mediumtext', 'longtext',
            'tinyblob', 'mediumblob', 'longblob', 'enum', 'set',
            'date', 'datetime', 'time', 'timestamp', 'year'
        ]);
    }


    /**
     * 创建时间
     *
     * @time 2021年03月13日
     * @return \think\migration\db\Column
     */
    protected function createCreateAtColumn(): \think\migration\db\Column
    {
        return (new TableColumn)->int('created_at', 10)
            ->setUnsigned()
            ->setDefault(0)
            ->setComment('创建时间');
    }

    /**
     * 更新时间
     *
     * @time 2021年03月13日
     * @return \think\migration\db\Column
     */
    protected function createUpdateAtColumn(): \think\migration\db\Column
    {
        return (new TableColumn)->int('updated_at', 10)
            ->setUnsigned()->setDefault(0)->setComment('更新时间');
    }

    /**
     * 软删除
     *
     * @time 2021年03月13日
     * @return \think\migration\db\Column
     */
    protected function createDeleteAtColumn(): \think\migration\db\Column
    {
        return (new TableColumn)->int('deleted_at', 10)
            ->setUnsigned()->setDefault(0)->setComment('软删除字段');
    }

    /**
     * 创建人
     *
     * @time 2021年03月13日
     * @return \think\migration\db\Column
     */
    protected function createCreatorIdColumn(): \think\migration\db\Column
    {
        return (new TableColumn)->int('creator_id', 10)
            ->setUnsigned()->setDefault(0)->setComment('创建人ID');
    }
}
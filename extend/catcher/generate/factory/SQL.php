<?php
namespace catcher\generate\factory;


use catcher\exceptions\FailedException;
use think\facade\Db;

class SQL extends Factory
{
    protected $index = '';


    public function done($params)
    {
        Db::execute($this->createSQL($params));

        // 判断表是否创建成功
        if (!$this->hasTableExists($params['table'])) {
            throw new FailedException(sprintf('create table [%s] failed', $params['table']));
        }

        return $params['table'];
    }

    /**
     * create table sql
     *
     * @time 2020年04月28日
     * @param $params
     * @return string
     */
    protected function createSQL($params)
    {
        if (!$params['table'] ?? false) {
            throw new FailedException('table name has lost~');
        }

        if ($this->hasTableExists($params['table'])) {
            throw new FailedException(sprintf('table [%s] has existed', $params['table']));
        }

        $extra = $params['extra'];
        // 主键
        $createSql = $this->primaryKey($extra['primary_key']);
        // 字段
        $ifHaveNotFields = true;
        foreach ($params['sql'] as $sql) {
            if (!$sql['field'] || !$sql['type']) {
                continue;
            }
            $ifHaveNotFields = false;
            $createSql .= $this->parseSQL($sql);
        }
        // 如果没有设置数据库字段
        if ($ifHaveNotFields) {
            throw new FailedException('Do you have set mysql fields?');
        }
        // 创建人
        if ($extra['creator_id'] ?? false) {
            $createSql .= $this->parseCreatorId();
        }
        // 创建时间
        if ($extra['created_at'] ?? false) {
            $createSql .= $this->parseCreatedAt();
        }
        // 软删除
        if ($extra['soft_delete'] ?? false) {
            $createSql .= $this->parseDeletedAt();
        }
        // 索引
        if ($this->index) {
            $createSql .= $this->index;
        }
        $createSql = rtrim($createSql, ',' . PHP_EOL);

        // 创建表 SQL
        return $this->createTable($params['table'], $createSql, $extra['engine'], 'utf8mb4', $extra['comment']);
    }

    /**
     * parse sql
     *
     * @time 2020年04月27日
     * @param $sql
     * @return string
     */
    protected function parseSQL($sql)
    {

        // 解析索引
        if ($sql['index']) {
            $this->parseIndex($sql['index'], $sql['field']);
        }

        // 字段
        $_sql[] = sprintf('`%s`', $sql['field']);
        // 类型
        $_sql[] = $sql['type'] . ($sql['length'] ? sprintf('(%s)', $sql['length']) : '');

        if ($sql['unsigned']) {
            $_sql[] = 'unsigned';
        }
        // 默认值
        $default = trim(trim($sql['default'], '\''));
        if (!$sql['nullable']) {
            $_sql[] = 'not null';
            if ($default == '' || $default === '') {
                if (!$this->doNotNeedDefaultValueType($sql['type'])) {
                    $_sql[] = ' default \'\'';
                }
            } else {
                if (strpos('int', $sql['type']) === false) {
                    $_sql[] = ' default ' . (int)$default ;
                } else {
                    $_sql[] = ' default ' . $default;
                }
            }
        }

        // 字段注释
        $_sql[] = $sql['comment'] ? sprintf('comment \'%s\'', $sql['comment']) : '';

        return implode(' ', $_sql) . ','. PHP_EOL;
    }

    /**
     * parse primary key
     *
     * @time 2020年04月27日
     * @param $id
     * @return string
     */
    protected function primaryKey($id)
    {
        return sprintf('`%s`', $id) . ' int unsigned not null auto_increment primary key,'. PHP_EOL;
    }

    /**
     * parse created_at & updated_at
     *
     * @time 2020年04月27日
     * @return string
     */
    protected function parseCreatedAt()
    {
        return sprintf('`created_at` int unsigned not null default 0 comment \'%s\',', '创建时间') . PHP_EOL .
            sprintf('`updated_at` int unsigned not null default 0 comment \'%s\',', '更新时间') . PHP_EOL;
    }

    /**
     * parse deleted_at
     *
     * @time 2020年04月27日
     * @return string
     */
    protected function parseDeletedAt()
    {
        return sprintf('`deleted_at` int unsigned not null default 0 comment \'%s\',', '软删除') . PHP_EOL;
    }

    /**
     * parse creator id
     *
     * @time 2020年07月01日
     * @return string
     */
    protected function parseCreatorId()
    {
        return sprintf('`creator_id` int unsigned not null default 0 comment \'%s\',', '创建人ID') . PHP_EOL;
    }

    /**
     * created table
     *
     * @time 2020年04月27日
     * @param $table
     * @param $sql
     * @param string $engine
     * @param string $charset
     * @param string $comment
     * @return string
     */
    protected function createTable($table, $sql, $engine='InnoDB', $charset = 'utf8mb4', $comment = '')
    {
        return sprintf('create table `%s`(' . PHP_EOL.
            '%s)'.PHP_EOL .
            'engine=%s default charset=%s comment=\'%s\'', $table, $sql, $engine, $charset, $comment);
    }

    /**
     * parse index
     *
     * @time 2020年04月27日
     * @param $index
     * @param $field
     * @return void
     */
    protected function parseIndex($index, $field)
    {
        if ($index == 'unique') {
            $this->index .= "unique index unique_$field($field)," . PHP_EOL;
        } elseif ($index == 'index') {
            $this->index .= "index($field),". PHP_EOL;
        } elseif ($index == 'fulltext') {
            $this->index .= "fulltext key fulltext_$field($field)," . PHP_EOL;
        } elseif ($index == 'spatial') {
            $this->index .= "spatial index spatial_$field($field),". PHP_EOL;
        }
    }


    /**
     * 不需要默认值
     *
     * @param string $type
     * @time 2020年10月23日
     * @return  bool
     */
    protected function doNotNeedDefaultValueType(string $type)
    {
        return in_array($type, [
            'blob', 'text', 'geometry', 'json',
            'tinytext', 'mediumtext', 'longtext',
            'tinyblob', 'mediumblob', 'longblob'
        ]);
    }
}
<?php
namespace JaguarJack\Generator\Factory;


class SQL
{
    protected $index = '';


    public function done($params)
    {
        if ($params['controller']['table'] ?? false) {
            return false;
        }

        $table = \config('database.connections.mysql.prefix') . $params['controller']['table'];

        $extra = $params['model']['extra'];

        // 主键
        $createSql = $this->primaryKey($extra['primary_key']);
        // 字段
        $ifHaveNotFields = true;
        foreach ($params['model']['data'] as $sql) {
            if (!$sql['field'] || !$sql['type']) {
                continue;
            }
            $ifHaveNotFields = false;
            $createSql .= $this->parseSQL($sql);
        }
        // 如果没有设置数据库字段
        if ($ifHaveNotFields) {
            return false;
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
        // 创建表 SQL
        return $this->createTable($table, $createSql, $extra['engine'], 'utf8mb4', $extra['comment']);
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

        return implode(' ', [
            sprintf('`%s`', $sql['field']),
            $sql['type'],
            $sql['length'] ? sprintf('(%s)', $sql['length']) : '',
            $sql['unsigned'] ? 'unsigned' : '',
            $sql['default'] ? 'default ' . $sql['default']: '',
            $sql['nullable'] ? 'not null' : '',
            $sql['comment'] ? sprintf('comment \'%s\'', $sql['comment']) : ''
        ]) . ','. PHP_EOL;
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
            $this->index .= "unique index unique_ . $field($field)";
        } elseif ($index == 'index') {
            $this->index .= "index($field)";
        } elseif ($index == 'fulltext') {
            $this->index .= "fulltext key fulltext_ .$field($field)";
        } elseif ($index == 'spatial') {
            $this->index .= "spatial index spatial_.$field($field)";
        }
    }
}
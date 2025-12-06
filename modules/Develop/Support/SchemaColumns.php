<?php

namespace Modules\Develop\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SchemaColumns
{
    public const INPUT_NUMBER = 'input-number';

    public const INPUT = 'input';

    public const SELECT = 'select';

    public const REMOTE_SELECT = 'remote-select';
    public const REMOTE_TREE_SELECT = 'remote-tree-select';
    public const REMOTE_TREE = 'remote-tree';
    public const REMOTE_CASCADER = 'remote-cascader';
    public const DATE = 'date';

    public const DATETIME = 'datetime';

    public const RADIO = 'radio';

    public const RATE = 'rate';

    public const CASCADER = 'cascader';

    public const TREE = 'tree';

    public const UPLOAD = 'upload';

    public const TREE_SELECT = 'tree-select';

    public const TEXTAREA = 'textarea';

    public const NUMBER_TYPE = ['tinyint', 'smallint', 'integer', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal'];

    public const STRING_TYPE = ['string', 'char', 'varchar', 'tinytext'];

    public const TEXT_TYPE = ['text', 'mediumtext', 'longtext'];

    public const DATE_TYPE = ['date'];

    public const DATETIME_TYPE = ['datetime', 'timestamp'];

    public const BOOLEAN_TYPE = ['boolean'];

    public function parse(array $columns)
    {
        $newColumns = [];
        foreach ($columns as $column) {
            $column['component'] = '';
            if ($column['comment']) {
                [$label, $options] = $this->parseComment($column['comment']);

                if (count($options)) {
                    $column['component'] = $this->selectOrRadioComponent($options);

                    $column['options'] = $options;
                }

                $column['label'] = $label;
            } else {
                $column['label'] = $column['comment'];
            }

            //  根据 column type 确定组件
            if (! $column['component']) {
                if (in_array($column['type'], self::NUMBER_TYPE)) {
                    $column['component'] = self::INPUT_NUMBER;
                }

                if (in_array($column['type'], self::STRING_TYPE)) {
                    $column['component'] = self::INPUT;
                }

                if (in_array($column['type'], self::TEXT_TYPE)) {
                    $column['component'] = self::TEXTAREA;
                }

                if (in_array($column['type'], self::DATE_TYPE)) {
                    $column['component'] = self::DATE;
                }

                if (in_array($column['type'], self::DATETIME_TYPE)) {
                    $column['component'] = self::DATETIME;
                }

                if (in_array($column['type'], self::BOOLEAN_TYPE)) {
                    $column['component'] = self::RADIO;
                }

                // 根据字段后缀确定
                if (Str::endsWith($column['name'], '_id')) {
                    $column['component'] = self::REMOTE_SELECT;
                    // 去除后缀得到表名
                    $tablaName = Str::remove('_id', $column['name']);
                    if (Schema::hasTable($tablaName)) {
                        $columns = Schema::getColumnListing($tablaName);

                        if (in_array('parent_id', $columns)) {
                            $column['component'] = self::REMOTE_CASCADER;
                        }
                    }
                }
            }

            // 自动在 form 中隐藏
            $column['form'] = ! in_array($column['name'], ['id', 'created_at', 'updated_at', 'deleted_at']);
            $newColumns[] = $column;
        }

        // 删除软删除字段
        foreach ($newColumns as $key => $column) {
            if ($column['name'] == 'deleted_at' || $column['name'] == 'creator_id') {
                unset($newColumns[$key]);
            }
        }

        return array_values($newColumns);
    }

    public function parseComment(string $comment): array
    {
        $comment = Str::of($comment);

        $options = [];

        if ($comment->contains(':')) {
            $comments = $comment->explode(':');
            $label = $comments->shift();

            $optionsString = $comments->pop();
            if (str_contains($optionsString, ',')) {
                Str::of($optionsString)->explode(',')
                    ->each(function ($option) use (&$options) {
                        if (str_contains($option, '=')) {
                            $option = Str::of($option)->explode('=');
                            $options[] = [
                                'value' => $option->shift(),
                                'label' => $option->pop(),
                            ];
                        }
                    });
            }
        } else {
            $label = $comment;
        }

        return [$label, $options];
    }

    protected function selectOrRadioComponent(array $options): string
    {
        if (count($options) <= 2) {
            return self::RADIO;
        }

        return self::SELECT;
    }
}

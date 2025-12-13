<?php

namespace Modules\Develop\Support\Generate\Create;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;

/**
 * 格式化数据
 */
trait Format
{
    /**
     * 获取在列表的字段
     *
     * @param  array  $structures
     * @return array
     */
    protected function getFieldsInList(array $structures): array
    {
        $fields = [];
        foreach ($structures as $structure) {
            if ($structure['list']) {
                $fields[] = $structure['field'];
            }
        }

        return $fields;
    }

    /**
     * 获取表格 label
     *
     * @param  array  $structures
     * @return array
     */
    public function getFieldLabelsInList(array $structures): array
    {
        $labels = [];
        foreach ($structures as $structure) {
            if ($structure['list']) {
                $labels[] = $structure['label'] ?: $structure['field'];
            }
        }

        return $labels;
    }

    /**
     * 获取开关字段
     *
     * @param  array  $structures
     * @return array
     */
    public function getSwitchFields(array $structures): array
    {
        $switchFields = [];
        foreach ($structures as $structure) {
            if ($structure['form_component'] === 'switch') {
                $switchFields[] = $structure['field'];
            }
        }

        return $switchFields;
    }

    /**
     * 枚举字段
     */
    protected function enumsFields(array $structures = []): array
    {
        $enumFields = [];
        foreach ($structures as $structure) {
            if (isset($structure['options']) && count($structure['options'])) {
                $newOptions = [];
                foreach ($structure['options'] as $option) {
                    $newOptions[$option['value']] = $option['label'];
                }
                // 这里需要倒叙以下，不然数组写入会丢失key
                krsort($newOptions);
                $enumFields[] = [
                    'field' => $structure['field'],
                    'field_text' => $structure['field'].'_text',
                    'options' => $newOptions,
                ];
            }
        }

        return $enumFields;
    }


    /**
     * 格式化关联关系数据
     */
    protected function formatRelations($relations): array
    {
        $relationModels = [];

        foreach ($relations as $relation) {
            $relationModel = [];
            if (! isset($relation['relation'])) {
                continue;
            }
            $relationModel['relation_method'] = $relation['relation'];
            $relationModel = array_merge($relationModel, $relation['data']);
            if (! empty($relationModel) && isset($relationModel['relation_method'])) {
                $relationModel['relation_class'] = $this->relationsMap()[$relationModel['relation_method']];
                $relationModels[] = $relationModel;
            }
        }

        return $relationModels;
    }

    /**
     * @return string[]
     */
    protected function relationsMap(): array
    {
        return [
            'hasOne' => HasOne::class,
            'hasMany' => HasMany::class,
            'belongsTo' => BelongsTo::class,
            'belongsToMany' => BelongsToMany::class,
            'hasOneThrough' => HasOneThrough::class,
            'hasManyThrough' => HasManyThrough::class,
        ];
    }

    /**
     * array to jsObject
     */
    protected function parseOptions2JsObject(array $options): string
    {
        $jsObject = Str::of('[');

        foreach ($options as $option) {
            $jsObject = $jsObject->append('{');

            if (is_numeric($option['value'])) {
                $jsObject = $jsObject->append(sprintf('label:\'%s\',value: %s,', $option['label'], intval($option['value'])));
            } else {
                $jsObject = $jsObject->append(sprintf('label:\'%s\',value:\'%s\',', $option['label'], $option['value']));
            }

            $jsObject = $jsObject->trim(',')->append('},');
        }

        return $jsObject->trim(',')->append(']')->toString();
    }
}

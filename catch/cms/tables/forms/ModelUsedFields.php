<?php
namespace catchAdmin\cms\tables\forms;

use catchAdmin\cms\model\Models;
use catcher\library\form\Form;
use think\facade\Db;

class ModelUsedFields extends Form
{
    public function fields(): array
    {
        $modelId = request()->param('model_id');

        $model = Models::where('id', $modelId)->with('fields')->find();

        $fields = $this->getModelFields($model->table_name);

        $this->primaryKeyValue = $modelId;
        // TODO: Implement fields() method.
        return [
            self::select('used_at_list', '列表使用', $model->used_at_list ? explode(',', $model->used_at_list) : '')
                ->multiple(true)
                ->style(['width' => '100%'])
                ->options($fields),

            self::select('used_at_search', '搜索使用' , $model->used_at_search ? explode(',', $model->used_at_search) : '')
                ->multiple(true)
                ->style(['width' => '100%'])
                ->options($fields),

            self::select('used_at_detail', '详情使用', $model->used_at_detail ? explode(',', $model->used_at_detail) : '')
                ->multiple(true)
                ->style(['width' => '100%'])
                ->options($fields),
        ];
    }

    /**
     * 获取模型字段
     *
     * @time 2021年05月11日
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return int[]|string[]
     */
    protected function getModelFields($tableName)
    {
        $fields = array_keys(Db::table($tableName)->getFields());

        foreach ($fields as $k => $field) {
            if ($field === app(Models::class)->getDeleteAtField()) {
                unset($fields[$k]);
            }
        }

        $options = self::options();

        foreach ($fields as $field) {
            $options = $options->add($field, $field);
        }


        return $options->render();
    }
}
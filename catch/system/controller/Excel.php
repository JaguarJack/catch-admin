<?php
namespace catchAdmin\system\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use think\Request;

class Excel extends CatchController
{
    /**
     * 导出
     *
     * @time 2021年04月22日
     * @param Request $request
     * @return \think\response\Json
     */
    public function export(Request $request): \think\response\Json
    {
        $fields = $this->resetFields(\json_decode($request->post('fields'), true));

        $excel = app()->make($request->post('model'))
            ->field(array_column($fields, 'field'))
            ->select()
            ->each(function (&$item, $key) use ($fields) {
                foreach ($fields as $field) {
                    if (isset($field['options']) && count($field['options'])) {
                        $options = $this->valueToLabel($field['options']);

                        $item[$field['field']] = $options[$item[$field['field']]] ?? '';
                    }
                }
        })->export(array_column($fields, 'name'));

        return CatchResponse::success($excel);
    }

    /**
     * 导入
     *
     * @time 2021年04月23日
     * @param Request $request
     * @return \think\response\Json
     */
    public function import(Request $request): \think\response\Json
    {
       return CatchResponse::success(app()->make($request->post('model'))
                ->import(
                    \json_decode($request->post('fields'), 'field'),
                    $request->file('file')
                ));

    }

    /**
     * value => label
     *
     * @time 2021年04月22日
     * @param array $options
     * @return array
     */
    protected function valueToLabel(array $options): array
    {
        $p = [];
        foreach ($options as $option) {
            $p[$option['value']] = $option['label'];
        }

        return $p;
    }

    /**
     *label => value
     *
     * @time 2021年04月22日
     * @param array $options
     * @return array
     */
    protected function labelToValue(array $options): array
    {
        $p = [];
        foreach ($options as $option) {
            $p[$option['label']] = $option['value'];
        }

        return $p;
    }

    /**
     * 重组 fields
     *
     * @time 2021年04月22日
     * @param array $fields
     * @return array
     */
    protected function resetFields(array $fields): array
    {
        $f = [];

        foreach ($fields as $field) {
            $f[$field['field']] = $field;
        }

        return $f;
    }
}
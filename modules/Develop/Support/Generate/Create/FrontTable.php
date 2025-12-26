<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FrontTable extends Creator
{
    protected string $columns = '{columns}';

    protected string $search = '{search}';

    protected string $api = '{api}';

    protected string $createForm = '{createForm}';

    protected string $tree = '{row-key}';

    protected string $paginate = '{paginate}';

    protected array $structures;

    protected string $createRoute = '{create_route}';

    public function __construct(
        protected readonly string $controller,
        protected readonly bool $hasPaginate,
        protected readonly string $apiString,
        protected readonly bool $needForm,
        protected readonly bool $isDynamic,
        protected readonly bool $isDialogForm,
        protected readonly array $operations = []
    ) {
    }

    /**
     * get content
     */
    public function getContent(): string
    {
        if ($this->isDynamic) {
            if ($this->isDialogForm) {
                return str_replace([$this->api], [$this->apiString . '/dynamic/r'], File::get($this->getTableStub()));
            } else {
                return str_replace(
                    [$this->api, $this->createRoute],
                    [$this->apiString . '/dynamic/r', '/' . $this->module . '/' . lcfirst($this->controller) . '/create'],
                    File::get($this->getTableStub())
                );
            }
        }

        $apiPathArr = explode('/', $this->apiString);
        $last = array_pop($apiPathArr);

        $hasSearchForm = $this->getSearchContent() ? ':search-form="search"' : '';
        $rowKey = $this->isTree() ? 'row-key="id"' : '';
        $paginate = $this->isTree() ? ':paginate="false"' : ($this->paginate ? '' : ':paginate="false"');

        $formDialog = $this->getCreateForm() ? $this->formDialog() : '';

        $searchForm = $hasSearchForm ? 'const search = ' . $this->getSearchContent() : '';
        $columns = 'const columns = ' . $this->getTableContent();
        $api = 'const api = "' . $this->apiString . '"';
        $operate = ! $this->getCreateForm() ? ':operation="false"' : '';
        // exportUrl="/user"
        //      importUrl="/user/import"
        $exportUrl = $importUrl = $exports = '';
        if (in_array('export', $this->operations)) {
            $exportUrl = implode('/', $apiPathArr) . '/export/' . $last;
            $exportUrl = sprintf('exportUrl="%s"', $exportUrl);
            $exports = ':exports="true"';
        }
        if (in_array('import', $this->operations)) {
            $importUrl = implode('/', $apiPathArr) . '/import/' . $last;
            $importUrl = sprintf('importUrl="%s"', $importUrl);
        }

        $str = <<<TEPLATE
<template>
  <div>
    <catch-table
      :columns="columns"
      :api="api"
      {$operate}
      {$hasSearchForm}
      {$rowKey}
      {$paginate}
      {$exports}
      {$exportUrl}
      {$importUrl}
    >
      {$formDialog}
    </catch-table>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
{$this->getCreateForm()}
{$api}

// table columns
{$columns}
// table search
{$searchForm}
</script>
TEPLATE;

        return preg_replace('/^\s*[\r\n]+/m', '', $str);
    }

    /**
     * get file
     */
    public function getFile(): string
    {
        $path = config('catch.views_path') . lcfirst($this->module) . DIRECTORY_SEPARATOR;

        return CatchAdmin::makeDir($path . Str::of($this->controller)->replace('Controller', '')->lcfirst()) . DIRECTORY_SEPARATOR . 'index.vue';
    }

    /**
     * get search content
     */
    protected function getSearchContent(): string
    {
        $hasSearch = false;
        $search = Str::of('[')->append(PHP_EOL);

        foreach ($this->structures as $structure) {
            if ($structure['search']) {
                $hasSearch = true;
                // options 专门使用 select 组件
                if ($structure['options'] ?? false) {
                    $structure['form_component'] = 'select';
                }
                $search = $search->append("\t{" . PHP_EOL)->append("\t")
                    ->append("\ttype: '{$structure['form_component']}'")->append(',' . PHP_EOL)->append("\t")
                    ->append("\tname: '{$structure['field']}'")->append(',' . PHP_EOL)->append("\t")
                    ->append("\tlabel: '{$structure['label']}'")->append(',' . PHP_EOL)->append("\t")
                    ->when($structure['options'] ?? false, function ($content) use ($structure) {
                        return $content->append("\toptions: {$this->parseOptions2JsObject($structure['options'])}")->append(',' . PHP_EOL)->append("\t");
                    })
                    // 如果是远程 select
                    ->when($structure['form_component'] == 'remote-select', function ($content) use ($structure) {
                        return $content->append("\tprops: {")
                            ->append("\t\ttable: '{$structure['remote_data_params']['table']}'")->append(',' . PHP_EOL)->append("\t\t")
                            ->append("\t\tvalue: '{$structure['remote_data_params']['value']}'")->append(',' . PHP_EOL)->append("\t\t")
                            ->append("\t\tlabel: '{$structure['remote_data_params']['label']}'")->append(',' . PHP_EOL)->append("\t\t")
                            ->when($structure['remote_data_params']['pid'], function ($content) use ($structure) {
                                return $content->append("\t\tpid: '{$structure['remote_data_params']['pid']}'")->append(',' . PHP_EOL)->append("\t\t");
                            })
                            ->append("\t}")->append(',' . PHP_EOL)->append("\t");
                    })
                    ->append('},')->append(PHP_EOL);
            }
        }

        return $hasSearch ? $search->trim(',')->append(']')->toString() : '';
    }

    /**
     * get list content;
     */
    protected function getTableContent(): string
    {
        $columns = Str::of('[')->append(PHP_EOL);

        $enumFields = [];

        foreach ($this->enumsFields($this->structures) as $enumField) {
            $enumFields[$enumField['field']] = $enumField['field_text'];
        }

        $switchFields = $this->getSwitchFields($this->structures);

        foreach ($this->structures as $structure) {
            if (! $structure['list']) {
                continue;
            }

            if ($structure['field'] == 'id') {
                $structure['label'] = $structure['label'] ?: 'ID';
            }

            // 转换 enum 字段
            if (isset($enumFields[$structure['field']]) && ! in_array($structure['field'], $switchFields)) {
                $structure['field'] = $enumFields[$structure['field']];
            }

            // 如果 label 为空，使用 field 作为 label
            $structure['label'] = $structure['label'] ?: $structure['field'];
            $columns = $columns->append("\t{" . PHP_EOL)->append("\t")
                ->append("\tprop: '{$structure['field']}'")->append(',' . PHP_EOL)->append("\t")
                ->append("\tlabel: '{$structure['label']}'")->append(',' . PHP_EOL)->append("\t")
                ->when(in_array($structure['field'], $switchFields), function ($content) {
                    return $content->append("\tswitch: true")->append(',' . PHP_EOL)->append("\t");
                })
                ->when(in_array($structure['form_component'], ['upload-oss', 'upload-image', 'upload-images']), function ($content) {
                    return $content->append("\timage: true")->append(',' . PHP_EOL)->append("\t");
                })
                ->when($structure['form_component'] == 'upload-images', function ($content) {
                    return $content->append("\tpreview: true")->append(',' . PHP_EOL)->append("\t");
                })
                ->append('},')->append(PHP_EOL);
        }

        $columns = $columns->append("\t{" . PHP_EOL)->append("\t")
            ->append("\ttype: 'operate'")->append(',' . PHP_EOL)->append("\t")
            ->append("\tlabel: '操作'")->append(',' . PHP_EOL)->append("\t")
            ->append('},')->append(PHP_EOL);

        return $columns->trim(',')->append(']')->toString();
    }

    /**
     * get table stub
     */
    protected function getTableStub(): string
    {
        if ($this->isDynamic && ! $this->isDialogForm) {
            $stub = 'tableDynamicNotDialogForm.stub';
        } else {
            $stub = $this->isDynamic ? ($this->needForm ? 'tableDynamic.stub' : 'tableDynamicNoForm.stub') : 'table.stub';
        }

        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'vue' . DIRECTORY_SEPARATOR . $stub;
    }

    /**
     * get tree props
     */
    public function isTree(): bool
    {
        return in_array('parent_id', array_column($this->structures, 'field'));
    }

    /**
     * set structures
     *
     * @return $this
     */
    public function setStructures(array $structures): static
    {
        $this->structures = $structures;

        return $this;
    }

    /**
     * get create form
     */
    protected function getCreateForm(): string
    {
        return $this->needForm ? "import Create from './form/create.vue'" : '';
    }

    protected function formDialog(): string
    {
        return <<<'TEXT'
<template #dialog="row">
        <Create :primary="row?.id" :api="api" />
    </template>
TEXT;
    }
}

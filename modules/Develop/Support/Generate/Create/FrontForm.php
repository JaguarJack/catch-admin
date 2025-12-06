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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\System\Models\DictionaryValues;

class FrontForm extends Creator
{
    protected string $label = '{label}';

    protected string $prop = '{prop}';

    protected string $modelValue = '{model-value}';

    protected string $table = '{table}';

    protected string $search = '{search}';

    protected string $api = '{api}';

    protected string $options = '{options}';

    protected string $formItems = '{formItems}';

    protected string $paginate = '{paginate}';

    protected string $defaultValue = '{defaultValue}';

    protected string $dicId = '{dic_id}';

    protected array $structures;

    protected string $tableName;

    public function __construct(
        protected readonly string $controller,
        protected readonly bool $isDynamic,
        protected readonly bool $isDialogForm,
        protected readonly string $apiString
    ) {
    }

    /**
     * get content
     */
    public function getContent(): string
    {
        if ($this->isDynamic) {
            return str_replace([$this->api], [$this->apiString.'/dynamic/r'], File::get($this->getFormStub()));
        }

        // TODO: Implement getContent() method.
        return Str::of(File::get($this->getFormStub()))->replace([$this->formItems, $this->defaultValue], [$this->getFormContent(), $this->getDefaultValues()])->toString();
    }

    /**
     * get file
     */
    public function getFile(): string
    {
        $path = config('catch.views_path').lcfirst($this->module).DIRECTORY_SEPARATOR;

        if ($this->isDialogForm) {
            // TODO: Implement getFile() method.
            return CatchAdmin::makeDir($path.Str::of($this->controller)->replace('Controller', '')->lcfirst().DIRECTORY_SEPARATOR.'form').DIRECTORY_SEPARATOR.'create.vue';
        } else {
            return CatchAdmin::makeDir($path.Str::of($this->controller)->replace('Controller', '')->lcfirst()).DIRECTORY_SEPARATOR.'create.vue';
        }
    }

    /**
     * get form content
     */
    protected function getFormContent(): string
    {
        $form = Str::of('');

        $formComponents = $this->formComponents();

        foreach ($this->structures as $structure) {
            if ($structure['form_component'] && $structure['form']) {
                if (isset($formComponents[$structure['form_component']])) {
                    $dictionaryId = $structure['dictionary'] ?? null;
                    $rules = $this->rules($structure['label'] ?? $structure['field'], $structure['validates']);
                    $form = $form->append(
                        Str::of($formComponents[$structure['form_component']])
                            ->replace(
                                [$this->label, $this->prop, $this->modelValue],
                                [$structure['label'] ?? $structure['field'], $structure['field'], sprintf('formData.%s', $structure['field'])]
                            )
                            ->when(isset($structure['options']), function ($content) use ($structure) {
                                return $content->replace($this->options, $this->parseOptions2JsObject($structure['options']));
                            })
                            ->when($dictionaryId, function ($content) use ($dictionaryId) {
                                return $content->replace($this->dicId, $dictionaryId);
                            })
                            // switch 组件
                            ->when($structure['form_component'] == 'switch' && isset($structure['options']), function ($content) use ($structure) {
                                $active = $structure['options'][0]['value'];
                                $inactive = $structure['options'][1]['value'];

                                return $content->replace(['{active}', '{inactive}'], [
                                    ! is_numeric($active) ? sprintf("'%s'", $active) : $active,
                                    ! is_numeric($inactive) ? sprintf("'%s'", $inactive) : $inactive,
                                ]);
                            })
                            // select & radio 组件
                            ->when(in_array($structure['form_component'], ['select', 'radio', 'checkbox']) && $dictionaryId, function ($content) use ($dictionaryId) {
                                return $content->replace($this->options, $this->parseOptions2JsObject(DictionaryValues::getEnabledValues($dictionaryId)->toArray()));
                            })
                            // remote 组件
                            ->when(Str::of($structure['form_component'])->startsWith('remote-'), function ($content) use ($structure) {
                                $remoteParams = $structure['remote_data_params'];

                                return $content->replace([
                                    '{table}', '{option-value}', '{option-label}', '{pid}',
                                ], [$remoteParams['table'] ?? '', $remoteParams['id'] ?? 'id', $remoteParams['label'] ?? 'name', $remoteParams['pid'] ?? 'parent_id']);
                            })
                            // 规则
                            ->when($rules, function ($content) use ($rules) {
                                return $content->replace('{rule}', $rules);
                            }, function ($content) {
                                return $content->replace('{rule}', '');
                            })
                    );
                }
            }
        }



        return $form->trim(PHP_EOL)->toString();
    }

    /**
     * 获取默认 value 值
     *
     * @return string
     */
    public function getDefaultValues(): string
    {
        // 默认值
        $defaultValue = Str::of('');
        $columns = [];
        foreach (Schema::getColumns($this->tableName) as $column) {
            $columns[$column['name']] = $column['default'];
        }

        foreach ($this->structures as $structure) {
            if (! $structure['form']) {
                continue;
            }
            $default = $columns[$structure['field']] ?? null;
            if (in_array($structure['form_component'], ['switch', 'radio', 'checkbox']) && isset($structure['options'])) {
                $values = array_column($structure['options'], 'value');
                $default = 0;
                if (! in_array($default, $values)) {
                    $default = $values[0];
                }
                $defaultValue = $defaultValue->append("formData.value.{$structure['field']} = {$default}")->append(PHP_EOL);
            }

            if ($structure['form_component'] == 'input-number') {
                if (is_null($default)) {
                    $default = 0;
                }
                $defaultValue = $defaultValue->append("formData.{$structure['field']} = {$default}")->append(PHP_EOL);
            }
        }

        return $defaultValue->trim(PHP_EOL)->append(PHP_EOL)->toString();
    }

    /**
     * form components
     */
    protected function formComponents(): array
    {
        $components = [];

        foreach (File::glob(
            $this->getFormItemStub()
        ) as $stub) {
            $newContent = '';
            // 删除第一行
            foreach (File::lines($stub) as $k => $line) {
                if ($k) {
                    $newContent .= $line.PHP_EOL;
                }
            }
            $components[File::name($stub)] = $newContent;
        }

        return $components;
    }

    /**
     * get formItem stub
     */
    protected function getFormItemStub(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'

            .DIRECTORY_SEPARATOR.'vue'.DIRECTORY_SEPARATOR

            .'formItems'.DIRECTORY_SEPARATOR.'*.stub';
    }

    /**
     * get form stub
     */
    public function getFormStub(): string
    {
        if ($this->isDynamic && ! $this->isDialogForm) {
            return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'

                .DIRECTORY_SEPARATOR.'vue'.DIRECTORY_SEPARATOR.'formPage.stub';
        }

        return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'

            .DIRECTORY_SEPARATOR.'vue'.DIRECTORY_SEPARATOR.($this->isDynamic ? 'formDynamic.stub' : 'form.stub');
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
     * @param  string  $table
     * @return $this
     */
    public function setTableName(string $table): static
    {
        $this->tableName = $table;

        return $this;
    }

    /**
     * @param  string  $label
     * @param  array  $validates
     * @return mixed
     */
    protected function rules(string $label, array $validates): mixed
    {
        $rules = [
            'string' => '必须是字符串类型',
            'number' => '必须是数字类型',
            'url' => 'URL 格式不正确',
            'email' => '邮箱格式不正确',
            'boolean' => '必须是布尔类型',
            'date' => '日期格式不正确',
            'required' => sprintf('%s字段必须填写', $label),
        ];

        $required = '{ required: true, message: \'%s\' },';

        $type = '{ type: \'%s\', message: \'%s\' },';

        $formRules = Str::of(':rules="[');

        $isHasFormRule = false;

        foreach ($validates as $validate) {
            if ($validate === 'numeric') {
                $validate = 'number';
            }

            if ($validate == 'required') {
                $isHasFormRule = true;
                $formRules = $formRules->append(sprintf($required, $rules[$validate] ?? ''));
            } else {
                if (in_array($validate, array_keys($rules))) {
                    $isHasFormRule = true;
                    $formRules = $formRules->append(sprintf($type, $validate, $rules[$validate]));
                }
            }
        }

        if (! $isHasFormRule) {
            return '';
        }

        return $formRules->trim(',')->append(']"')->toString();
    }
}

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
    /**
     * @var string
     */
    protected string $label = '{label}';

    /**
     * @var string
     */
    protected string $prop = '{prop}';

    /**
     * @var string
     */
    protected string $modelValue = '{model-value}';

    /**
     * @var string
     */
    protected string $table = '{table}';

    /**
     * @var string
     */
    protected string $search = '{search}';

    /**
     * @var string
     */
    protected string $api = '{api}';

    /**
     * @var string
     */
    protected string $formItems = '{formItems}';

    /**
     * @var string
     */
    protected string $paginate = '{paginate}';

    /**
     * @var string
     */
    protected string $useList = '{useList}';

    /**
     * @var string
     */
    protected string $tree = '{tree}';

    /**
     * @var array
     */
    protected array $structures;

    /**
     * @param string $controller
     * @param bool $hasPaginate
     * @param string $apiString
     */
    public function __construct(
        protected readonly string $controller,
        protected readonly bool $hasPaginate,
        protected readonly string $apiString
    ) {
    }

    /**
     * get content
     *
     * @return string
     */
    public function getContent(): string
    {
        // TODO: Implement getContent() method.
        return Str::of(File::get($this->getTableStub()))->replace([
            $this->table, $this->search, $this->api, $this->paginate, $this->useList, $this->tree
        ], [
            $this->getTableContent(),
            $this->getSearchContent(),
            $this->apiString,
            $this->getPaginateStubContent(),
            $this->getUseList(),
            $this->getTreeProps()
        ])->toString();
    }

    /**
     * get file
     *
     * @return string
     */
    public function getFile(): string
    {
        // TODO: Implement getFile() method.
        $path = config('catch.views_path').lcfirst($this->module).DIRECTORY_SEPARATOR;

        return CatchAdmin::makeDir($path.Str::of($this->controller)->replace('Controller', '')->lcfirst()).DIRECTORY_SEPARATOR.'index.vue';
    }


    /**
     * get search content
     *
     * @return string
     */
    protected function getSearchContent(): string
    {
        $search = Str::of('');

        $formComponents = $this->formComponents();

        foreach ($this->structures as $structure) {
            if ($structure['label'] && $structure['form_component'] && $structure['search']) {
                if (isset($formComponents[$structure['form_component']])) {
                    $search = $search->append(
                        Str::of($formComponents[$structure['form_component']])
                            ->replace(
                                [$this->label, $this->prop, $this->modelValue],
                                [$structure['label'], $structure['field'], sprintf('query.%s', $structure['field'])]
                            )
                    );
                }
            }
        }

        return $search->trim(PHP_EOL)->toString();
    }

    /**
     * get list content;
     *
     * @return string
     */
    protected function getTableContent(): string
    {
        $tableColumn = <<<HTML
<el-table-column prop="{prop}" label="{label}" />
HTML;

        $table = Str::of('');

        foreach ($this->structures as $structure) {
            if ($structure['field'] && $structure['label'] && $structure['list']) {
                $table = $table->append(
                    Str::of($tableColumn)->replace([$this->label, $this->prop], [$structure['label'], $structure['field']])
                )->newLine();
            }
        }

        return $table->trim(PHP_EOL)->toString();
    }

    /**
     * form components
     *
     * @return array
     */
    protected function formComponents(): array
    {
        $components = [];

        foreach (File::glob(
            $this->getFormItemStub()
        ) as $stub) {
            $components[File::name($stub)] = File::get($stub);
        }

        return $components;
    }


    /**
     * get formItem stub
     *
     * @return string
     */
    protected function getFormItemStub(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'

            .DIRECTORY_SEPARATOR.'vue'.DIRECTORY_SEPARATOR

            .'formItems'.DIRECTORY_SEPARATOR.'*.stub';
    }


    /**
     * get table stub
     *
     * @return string
     */
    protected function getTableStub(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'

            .DIRECTORY_SEPARATOR.'vue'.DIRECTORY_SEPARATOR.'table.stub';
    }

    /**
     * get paginate stub content
     *
     * @return string
     */
    protected function getPaginateStubContent(): string
    {
        return $this->hasPaginate ? '<Paginate />' : '';
    }

    /**
     * get use List
     * @return string
     */
    protected function getUseList(): string
    {
        if ($this->hasPaginate) {
            return 'const { data, query, search, reset, loading } = useGetList(api)';
        } else {
            return 'const { data, query, search, reset, loading } = useGetList(api, false)';
        }
    }

    /**
     * get tree props
     *
     * @return string
     */
    public function getTreeProps(): string
    {
        if (in_array('parent_id', array_column($this->structures, 'field'))) {
            return ' row-key="id" default-expand-all :tree-props="{ children: \'children\' }"';
        }

        return ' ';
    }

    /**
     * set structures
     *
     * @param array $structures
     * @return $this
     */
    public function setStructures(array $structures): static
    {
        $this->structures = $structures;

        return $this;
    }
}

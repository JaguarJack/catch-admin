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

class FrontForm extends Creator
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
     * @var array
     */
    protected array $structures;

    /**
     * @param string $controller
     */
    public function __construct(protected readonly string $controller)
    {
    }

    /**
     * get content
     *
     * @return string
     */
    public function getContent(): string
    {
        // TODO: Implement getContent() method.
        return Str::of(File::get($this->getFormStub()))->replace($this->formItems, $this->getFormContent())->toString();
    }

    /**
     * get file
     *
     * @return string
     */
    public function getFile(): string
    {
        $path = config('catch.views_path').lcfirst($this->module).DIRECTORY_SEPARATOR;

        // TODO: Implement getFile() method.
        return CatchAdmin::makeDir($path.Str::of($this->controller)->replace('Controller', '')->lcfirst()).DIRECTORY_SEPARATOR.'create.vue';
    }

    /**
     * get form content
     *
     * @return string
     */
    protected function getFormContent(): string
    {
        $form = Str::of('');

        $formComponents = $this->formComponents();

        foreach ($this->structures as $structure) {
            if ($structure['label'] && $structure['form_component'] && $structure['form']) {
                if (isset($formComponents[$structure['form_component']])) {
                    $form = $form->append(
                        Str::of($formComponents[$structure['form_component']])
                            ->replace(
                                [$this->label, $this->prop, $this->modelValue],
                                [$structure['label'], $structure['field'], sprintf('formData.%s', $structure['field'])]
                            )
                    );
                }
            }
        }

        return $form->trim(PHP_EOL)->toString();
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
     * get form stub
     *
     * @return string
     */
    public function getFormStub(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'

            .DIRECTORY_SEPARATOR.'vue'.DIRECTORY_SEPARATOR.'form.stub';
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

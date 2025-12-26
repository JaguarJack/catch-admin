<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use CatchForm\Builder;
use CatchForm\Form;
use CatchForm\Table\Table;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\PhpFile;

class Dynamic extends Creator
{
    public function __construct(
        protected string $controller,
        protected array $structures,
        protected readonly bool $needForm,
        protected string $api,
        protected bool $isDialogForm
    ) {
    }

    public function getFile(): string
    {
        CatchAdmin::makeDir(CatchAdmin::getModulePath($this->module) . 'Dynamics');

        // TODO: Implement getFile() method.
        return CatchAdmin::getModulePath($this->module) . 'Dynamics' . DIRECTORY_SEPARATOR . $this->controller . $this->ext;
    }

    public function getContent(): string|bool|PhpFile
    {
        // TODO: Implement getContent() method.
        $file = new PhpFile();
        $file->setStrictTypes();

        $namespace = $file->addNamespace($this->getDynamicNamespace());
        $namespace->addUse(Builder::class);
        if ($this->needForm) {
            $namespace->addUse(Form::class);
        }
        $namespace->addUse(Table::class);

        $class = $namespace->addClass($this->controller)->setExtends('Builder');

        $class->addMethod('table')
            ->setBody($this->getTableStr())
            ->setComment('动态表格')->setReturnType('mixed');

        if ($this->needForm) {
            $class->addMethod('form')
                ->setBody('return Form::make(function (Form $form) {
' . $this->getFormStr() . '
});')->addComment('动态表单')->setReturnType('mixed');
        } else {
            $class->addMethod('form')
                ->setBody('return [];')
                ->addComment('动态表单')
                ->setReturnType('mixed');
        }

        return $file;
    }

    /**
     * 表格栏目
     *
     * @return string
     */
    protected function getTableStr(): string
    {
        $body = '';
        $isTree = false;
        foreach ($this->structures as $structure) {
            if ($structure['list']) {
                if ($structure['field'] === 'id') {
                    $body .= "\t\$table->id();\n";
                } else {
                    $body .= sprintf("\t\$table->column('%s', '%s');\n", $structure['field'], $structure['label'] ?: $structure['field']);
                }
            }

            if (in_array($structure['field'], ['pid', 'parent_id'])) {
                $isTree = true;
            }
        }

        // 不使用 form
        if (! $this->needForm) {
            $body .= "\t\$table->operate()->hideUpdate();\n";
        } else {
            if ($this->isDialogForm) {
                $body .= "\t\$table->operate();\n";
            } else {
                $body .= "\t\$table->operate()->hideUpdate();\n";
            }
        }

        $body = trim($body, "\n");

        $table = 'return Table::make(\'' . $this->api . '\')->columns(function (Table $table){
' . $body . '
})';

        if ($isTree) {
            $table .= '->rowKey()';
        }

        // 不使用弹窗的话，隐藏预置按钮
        if (! $this->isDialogForm || ! $this->needForm) {
            $table .= '->hideOperation()';
        }

        return $table . ';';
    }

    protected function getFormStr(): string
    {
        $dumper = new Dumper();
        $body = '';
        foreach ($this->structures as $structure) {
            if ($structure['form'] && $structure['form_component']) {
                $body .= sprintf("\t\$form->%s('%s', '%s')", $structure['form_component'], $structure['field'], $structure['label'] ?: $structure['field']);

                if (in_array('required', $structure['validates'])) {
                    $body .= '->required()';
                }

                if (! empty($structure['options'])) {
                    foreach ($structure['options'] as &$option) {
                        if (is_numeric($option['value'])) {
                            $option['value'] = intval($option['value']);
                        }
                    }

                    $body .= '->options(' . $dumper->dump($structure['options']) . ')';
                }

                $body .= ";\n";
            }
        }

        return trim($body, "\n");
    }

    /**
     * get model namespace
     */
    public function getDynamicNamespace(): string
    {
        return CatchAdmin::getModuleNamespace($this->module) . 'Dynamics';
    }
}

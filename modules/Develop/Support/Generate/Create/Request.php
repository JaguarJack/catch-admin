<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Nette\PhpGenerator\PhpFile;

class Request extends Creator
{
    protected array $structures;

    public function __construct(public readonly string $controller)
    {
    }

    public function getFile(): string
    {
        return CatchAdmin::getModuleRequestPath($this->module).$this->getRequestName().$this->ext;
    }

    /**
     * get content
     */
    public function getContent(): string|bool|PhpFile
    {
        $fieldRules = $this->getRules();

        if (! count($fieldRules)) {
            return false;
        }

        $file = new PhpFile();
        $file->setStrictTypes();

        $namespace = $file->addNamespace($this->getNamespace());

        $namespace->addUse(FormRequest::class);

        $class = $namespace->addClass($this->getRequestName())->setExtends('FormRequest')
            ->addComment('验证表单')
            ->addComment('@class '.$this->getRequestName());

        $class->addProperty('stopOnFirstFailure', true)->setProtected()->addComment('验证错误立即停止');

        $class->addMethod('rules')
            // ->addBody('$text = ?;'."\n", [$enumField['options']])
            ->addBody('return ?;', [$fieldRules])
            ->setReturnType('array')
            ->addComment('验证规则')
            ->addComment('@return array');

        // 'required',
        //  'integer',
        //  'numeric',
        //  'string',
        //  'timezone',
        //  'url',
        //  'uuid',
        //  'date',
        //  'alpha',
        //  'alpha_dash',
        //  'alpha_num',
        //  'boolean',
        //  'email',
        //  'image',
        //  'file',
        //  'ip',
        //  'ipv4',
        //  'ipv6',
        //  'mac_address',
        //  'json',
        //  'nullable',
        //  'present',
        //  'prohibited'
        $messages = [];
        $fieldLabel = [];
        foreach ($this->structures as $structure) {
            $fieldLabel[$structure['field']] = $structure['label'];
        }

        $ruleMessages = $this->ruleMessage();
        foreach ($fieldRules as $field => $rules) {
            foreach (explode('|', $rules) as $rule) {
                $message = $ruleMessages[$rule] ?? null;
                if ($message) {
                    $messages["{$field}.{$rule}"] = sprintf($message, $fieldLabel[$field] ?? $field);
                }
            }
        }

        $class->addMethod('messages')
            ->addBody('return ?;', [$messages])
            ->setReturnType('array')
            ->addComment('验证规则信息')
            ->addComment('@return array');

        return $file;
    }

    /**
     * @return string[]
     */
    protected function ruleMessage(): array
    {
        return [
            'required' => '%s必填',
            'integer' => '%s必须是整数',
            'numeric' => '%s必须是数字',
            'string' => '%s必须是字符串',
            'timezone' => '%s必须是有效的时区',
            'url' => '%s必须是有效的URL',
            'uuid' => '%s必须是有效的UUID',
            'date' => '%s必须是有效的日期',
            'alpha' => '%s只能包含字母',
            'alpha_dash' => '%s只能包含字母、数字、破折号和下划线',
            'alpha_num' => '%s只能包含字母和数字',
            'boolean' => '%s必须是布尔值',
            'email' => '%s必须是有效的电子邮件地址',
            'image' => '%s必须是图片',
            'file' => '%s必须是文件',
            'ip' => '%s必须是有效的IP地址',
            'ipv4' => '%s必须是有效的IPv4地址',
            'ipv6' => '%s必须是有效的IPv6地址',
            'mac_address' => '%s必须是有效的MAC地址',
            'json' => '%s必须是有效的JSON字符串',
            'nullable' => '%s可以为空',
            'present' => '%s必须存在',
            'prohibited' => '%s字段被禁止',
        ];
    }

    /**
     * get namespace
     */
    protected function getNamespace(): string
    {
        return Str::of(CatchAdmin::getModuleRequestNamespace($this->module))->rtrim('\\')->toString();
    }

    /**
     * get request name
     *
     * @return ?string
     */
    public function getRequestName(): ?string
    {
        if ($this->getRules()) {
            return Str::of($this->controller)->remove('Controller')->append('Request')->ucfirst()->toString();
        }

        return null;
    }

    /**
     * get rules
     */
    protected function getRules(): array
    {
        $rules = [];

        foreach ($this->structures as $structure) {
            if ($structure['field'] && count($structure['validates'])) {
                $rules[$structure['field']] = implode('|', $structure['validates']);
            }
        }

        return $rules;
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
}

<?php

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Catch\Enums\Enum;
use Illuminate\Support\Str;
use Nette\PhpGenerator\PhpFile;

class Enumer extends Creator
{
    /**
     * @param  string  $title
     * @param  string  $description
     * @param  string  $enumClass
     * @param  array<array<label, value>>  $values
     */
    public function __construct(
        protected string $title,
        protected string $description,
        protected string $enumClass,
        protected array $values
    ) {
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        $enmDir = CatchAdmin::makeDir(
            CatchAdmin::getModulePath('Common').DIRECTORY_SEPARATOR.'Enums'
        );

        return $enmDir.DIRECTORY_SEPARATOR.$this->enumClass.'.php';
    }

    /**
     * @return string|bool|PhpFile
     */
    public function getContent(): string|bool|PhpFile
    {
        $file = new PhpFile();
        $file->setStrictTypes();

        $namespace = $file->addNamespace($this->getEnumNameSpace());

        $namespace->addUse(Enum::class);

        $enum = $namespace->addEnum($this->enumClass)->addImplement('Enum')
            ->addComment('@title '.$this->title)
            ->addComment('@description '.$this->description)
            ->addComment('@class '.$this->enumClass);

        $names = $values = "\n";
        foreach ($this->values as $value) {
            $k = Str::of($value['key'])->upper()->toString();
            $v = is_numeric($value['value']) ? intval($value['value']) : $value['value'];
            $enum->addCase($k, $v);

            $names .= sprintf("\tself::%s => '%s', \n", $k, $value['label']);
            $values .= sprintf("\tself::%s => %s, \n", $k, $v);
        }

        $enum->addMethod('name')
            ->setReturnType('string')
            ->setBody('return match($this) { '.$names.'};');

        $enum->addMethod('value')
            ->setReturnType('string|int')
            ->setBody('return match($this){'.$values.'};');

        $enum->addMethod('assert')
            ->setBody('return $this->value === $value;')
            ->setReturnType('bool')
            ->addComment('判断值是否匹配')
            ->addComment('@param mixed $value')
            ->addComment('@return bool')
            ->addParameter('value')->setType('mixed');

        return $file;
    }

    protected function getEnumNameSpace(): string
    {
        return 'Modules\Common\Enums';
    }
}

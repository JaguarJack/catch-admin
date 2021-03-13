<?php
namespace catcher\library\form;

use catcher\exceptions\FailedException;

abstract class FormFactory
{

    abstract public static function from();

    /**
     * 创建 Form
     *
     * @time 2021年03月02日
     * @param $name
     * @return mixed
     */
    public static function create($name)
    {
        $form = static::from() . '\\'. ucfirst($name);

        if (!class_exists($form)) {
            throw new FailedException(sprintf('Form [%s] not found! Please create it first', ucfirst($name)));
        }

        $form = app($form);

        if (!$form instanceof Form) {
            throw new FailedException(sprintf('Form [%s] must implements interface [FormCreate]', ucfirst($name)));
        }

        return $form->create();
    }
}
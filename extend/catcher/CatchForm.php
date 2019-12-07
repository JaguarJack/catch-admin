<?php
namespace catcher;

/**
 * Class CatchForm
 * @package catcher
 *
 *
 * @method CatchForm text($column, $label = '')
 * @method CatchForm image($column, $label = '')
 * @method CatchForm radio($column, $label = '')
 * @method CatchForm select($column, $label = '')
 * @method CatchForm textarea($column, $label = '')
 * @method CatchForm password($column, $label = '')
 *
 */
class CatchForm
{
    protected $name;

    private $fields = [];

    protected $action;

    protected $method;

    protected $enctype;

    protected $formId;

    protected $btn;

    public function action($acton)
    {
        $this->action = $acton;

        return $this;
    }

    public function method($method)
    {
        $this->method = $method;

        return $this;
    }

    public function formId($formId)
    {
        $this->formId = $formId;

        return $this;
    }

    public function enctype($enctype ="multipart/form-data")
    {
        $this->enctype = $enctype;

        return $this;
    }

    public function id($id)
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'id' => sprintf('id="%s"', $id),
        ]);
        return $this;
    }


    public function class($class='', $labelClass = '', $inlineClass = '')
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'class' => $class,
            'labelClass' => $labelClass,
            'inlineClass' => $inlineClass,
        ]);

        return $this;
    }

    public function options(array $options)
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'options' => $options,
        ]);

        return $this;
    }

    public function default($value)
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'default' => $value,
        ]);

        return $this;
    }


    public function disabled()
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'disabled' => '',

        ]);

        return $this;
    }
    public function placeholder($content)
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'placeholder' => 'placeholder='.$content,
        ]);

        return $this;
    }

    public function readonly()
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'readonly' => 'readonly',
        ]);

        return $this;
    }


    public function render()
    {
        $form = sprintf('<form id="%s" lay-filter="%s" class="layui-form model-form">', $this->formId, $this->formId);

        foreach ($this->fields as $field) {
            $form .= sprintf($this->baseField(),
                $field['labelClass'] ?? '',
                $field['label'],
                $field['inlineClass'] ?? '',
                $this->{$field['type'].'Field'}($field)) ;

        }

       return $form . $this->btn. '</form>';
    }

    public function append($append)
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'append' => $append,
        ]);

        return $this;
    }

    public function __call($method, $arguments)
    {
        // TODO: Implement __call() method.
        $this->name = $arguments[0] ?? '';
        $label = $arguments[1] ?? '';

        $this->fields[$this->name] = [
            'name' => $this->name,
            'type' => $method,
            'label' => $label,
            'inline' => false,
        ];

        return $this;
    }

    protected function inline()
    {
        $this->fields[] = array_merge($this->fields, [
            'inline' => true,
        ]);

        return $this;
    }

    private function baseField()
    {
        return
        '<div class="layui-form-item">
            <label class="layui-form-label%s">%s: </label>
            <div class="layui-input-block%s">
               %s 
            </div>
        </div>';
    }

    /**
     * form btn
     *
     * @time 2019年12月06日
     * @param $filter
     * @param string $position
     * @return string
     */
    public function formBtn($filter, $position = 'text-right')
    {
       $this->btn = sprintf('<div class="layui-form-item %s">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closePageDialog">取消</button>
        <button class="layui-btn" lay-filter="%s" lay-submit>保存</button>
        </div>', $position, $filter);

       return $this;
    }

    public function verify($rule, $equalTo = [])
    {
        if (empty($equalTo)) {
            $this->fields[$this->name] = array_merge($this->fields[$this->name], [
                'verify' => sprintf('lay-verType="tips" lay-verify="%s"', $rule),
            ]);
        } else {
            [$id, $msg] = $equalTo;

            $this->fields[$this->name] = array_merge($this->fields[$this->name], [
                'verify' => sprintf(' lay-verType="tips" lay-verify="%s" lay-equalTo="#%s"
                            lay-equalToText="%s" ', $rule, $id, $msg),
            ]);
        }

        return $this;
    }

    private function textField($field)
    {
        return
            sprintf('<input name="%s" class="layui-input %s" %s value="%s" type="text" %s %s %s%s>',
                $field['name'],
                $field['id'] ?? '',
                $field['class'] ?? '',
                $field['default'] ?? '',
                $field['readonly'] ?? '',
                $field['placeholder'] ?? '',
                $field['disabled'] ?? '',
                $field['verify'] ?? ''
        );

    }

    private function selectField($field)
    {
       $select = sprintf('<select name="%s">', $field['name']);

       $default = $field['default'] ?? '';

       foreach ($field['options'] as $key => $option) {
           $select .= sprintf('<option value="%s"%s>%s</option>', $key, $default == $key ? ' selected' : '',$option);
       }

       return $select . '</select>';
    }

    private function passwordField($field)
    {
        return sprintf('<input name="%s" class="layui-input" %s type="password" %s %s>',
            $field['name'],
            $field['id'] ?? '',
            $field['verify'] ?? '',
            $field['placeholder'] ?? ''
        );
    }

    private function radioField()
    {}

    private function textareaField()
    {}

    private function imageField()
    {}

}

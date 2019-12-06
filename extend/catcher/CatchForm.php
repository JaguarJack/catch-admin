<?php
namespace catcher;

/**
 * Class CatchForm
 * @package catcher
 *
 *
 * @method text($column, $label = ''): self
 * @method image($column, $label = ''): self
 * @method radio($column, $label = ''): self
 * @method select($column, $label = ''): self
 * @method textarea($column, $label = ''): self
 *
 */
class CatchForm
{
    protected $name;

    private $fields = [];

    public function id($id)
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'id' => $id,
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
        $form = '';
        foreach ($this->fields as $field) {
            $form .= sprintf($this->baseField(),
                $field['labelClass'] ?? '',
                $field['label'],
                $field['inlineClass'] ?? '',
                $this->{$field['type'].'Field'}($field));

        }

       return $form;
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
        '<div class="layui-inline">
            <label class="layui-form-label %s">%s: </label>
            <div class="layui-input-inline %s">
               %s 
            </div>
        </div>';
    }
    private function textField($field)
    {
        return
            sprintf('<input name="%s" class="layui-input %s" value="%s" type="text" %s %s %s>',
                $field['name'],
                $field['class'],
                $field['default'] ?? '',
                $field['readonly'] ?? '',
                $field['placeholder'] ?? '',
                $field['disabled'] ?? ''
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

    private function radioField()
    {}

    private function textareaField()
    {}

    private function imageField()
    {}

}

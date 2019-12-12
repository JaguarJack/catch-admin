<?php
namespace catcher;

/**
 * Class CatchForm
 * @package catcher
 *
 *
 * @method CatchForm text($column, $label = '', $required = false)
 * @method CatchForm image($column, $label = '', $required = false)
 * @method CatchForm radio($column, $label = '', $required = false)
 * @method CatchForm select($column, $label = '', $required = false)
 * @method CatchForm textarea($column, $label = '', $required = false)
 * @method CatchForm password($column, $label = '', $required = false)
 * @method CatchForm hidden($column, $label = '', $required = false)
 * @method CatchForm dom($column, $label = '', $required = false)
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

    /**
     *
     * @time 2019年12月10日
     * @param $acton
     * @return CatchForm
     */
    public function action($acton): CatchForm
    {
        $this->action = $acton;

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param $method
     * @return CatchForm
     */
    public function method($method): CatchForm
    {
        $this->method = $method;

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param $formId
     * @return CatchForm
     */
    public function formId($formId): CatchForm
    {
        $this->formId = $formId;

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param string $enctype
     * @return CatchForm
     */
    public function enctype($enctype ="multipart/form-data"): CatchForm
    {
        $this->enctype = $enctype;

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param $id
     * @return CatchForm
     */
    public function id($id): CatchForm
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'id' => sprintf('id="%s"', $id),
        ]);
        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param string $class
     * @param string $labelClass
     * @param string $inlineClass
     * @return CatchForm
     */
    public function class($class='', $labelClass = '', $inlineClass = ''): CatchForm
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'class' => $class,
            'labelClass' => $labelClass,
            'inlineClass' => $inlineClass,
        ]);

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param array $options
     * @return CatchForm
     */
    public function options(array $options): CatchForm
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'options' => $options,
        ]);

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param $value
     * @return CatchForm
     */
    public function default($value): CatchForm
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'default' => $value,
        ]);

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @return CatchForm
     */
    public function disabled(): CatchForm
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'disabled' => '',

        ]);

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param $content
     * @return CatchForm
     */
    public function placeholder($content): CatchForm
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'placeholder' => 'placeholder='.$content,
        ]);

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @return CatchForm
     */
    public function readonly(): CatchForm
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'readonly' => 'readonly',
        ]);

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @return string
     */
    public function render(): string
    {
        $form = sprintf('<form id="%s" lay-filter="%s" class="layui-form model-form">', $this->formId, $this->formId);

        foreach ($this->fields as $field) {
            $form .= in_array($field['type'], ['hidden']) ?
                $this->{$field['type'].'Field'}($field)
                : sprintf($this->baseField(),
                $field['labelClass'] ?? '',
                $field['label'],
                $field['inlineClass'] ?? '',
                $this->{$field['type'].'Field'}($field));
        }

       return $form . $this->btn. '</form>';
    }

    /**
     *
     * @time 2019年12月10日
     * @param $append
     * @return CatchForm
     */
    public function append($append): CatchForm
    {
        $this->fields[$this->name] = array_merge($this->fields[$this->name], [
            'append' => $append,
        ]);

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param $method
     * @param $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        // TODO: Implement __call() method.
        $this->name = $arguments[0] ?? '';
        $label = $arguments[1] ?? '';
        $required = $arguments[2] ?? false;

        $this->fields[$this->name] = [
            'name' => $this->name,
            'type' => $method,
            'label' => $required ? '<i style="color:red">*</i> '.$label : $label,
            'inline' => false,
        ];

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @return CatchForm
     */
    protected function inline(): CatchForm
    {
        $this->fields[] = array_merge($this->fields, [
            'inline' => true,
        ]);

        return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @return string
     */
    private function baseField(): string
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
     * @return CatchForm
     */
    public function formBtn($filter, $position = 'text-right'): CatchForm
    {
       $this->btn = sprintf('<div class="layui-form-item %s">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closePageDialog">取消</button>
        <button class="layui-btn" lay-filter="%s" lay-submit>保存</button>
        </div>', $position, $filter);

       return $this;
    }

    /**
     *
     * @time 2019年12月10日
     * @param $rule
     * @param array $equalTo
     * @return CatchForm
     */
    public function verify($rule, $equalTo = []): CatchForm
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

    /**
     *
     * @time 2019年12月10日
     * @param $field
     * @return string
     */
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

    /**
     *
     * @time 2019年12月10日
     * @param $field
     * @return string
     */
    private function selectField($field)
    {
       $select = sprintf('<select name="%s" %s>', $field['name'], $field['verify'] ?? '');

       $default = $field['default'] ?? '';

       foreach ($field['options'] as $key => $option) {
           $select .= sprintf('<option value="%s"%s>%s</option>', $option['value'], $default == $key ? ' selected' : '',$option['title']);
       }

       return $select . '</select>';
    }

    /**
     *
     * @time 2019年12月10日
     * @param $field
     * @return string
     */
    private function passwordField($field)
    {
        return sprintf('<input name="%s" class="layui-input" %s type="password" %s %s>',
            $field['name'],
            $field['id'] ?? '',
            $field['verify'] ?? '',
            $field['placeholder'] ?? ''
        );
    }

    private function radioField($field)
    {
        $radio = '';
        foreach ($field['options'] as $option) {
            $radio .= sprintf('<input name="%s" type="radio" value="%s" title="%s" %s/>',
                $field['name'], $option['value'], $option['title'], $option['value'] == $field['default'] ? 'checked' : ''
            );
        }

        return $radio;
    }

    /**
     *
     * @time 2019年12月09日
     * @param $field
     * @return string
     */
    private function textareaField($field): string
    {
        return sprintf('<textarea name="%s" %s class="layui-textarea">%s</textarea>',
            $field['name'],
            $field['placeholder'] ?? '',
            $field['default'] ?? ''
        );
    }

    private function domField($field)
    {
        return $field['name'];
    }

    /**
     *
     * @time 2019年12月10日
     * @param $field
     * @return string
     */
    private function hiddenField($field): string
    {
        return sprintf('<input name="%s" value="%s" type="hidden">',
                $field['name'], $field['default']
        );
    }

    private function imageField()
    {}

}

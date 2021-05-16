<?php
namespace catcher\library\form\components;

use catcher\library\form\Form;
use FormBuilder\Driver\FormComponent;
use FormBuilder\Factory\Elm;

class Editor extends FormComponent
{
    protected $defaultProps = [
        'type' => 'editor'
    ];

    protected $defaultPlugins = ['advlist anchor autolink autosave lists code codesample colorpicker colorpicker contextmenu directionality emoticons fullscreen hr image imagetools insertdatetime link lists media nonbreaking noneditable pagebreak paste preview print save searchreplace spellchecker tabfocus table template textcolor textpattern visualblocks visualchars wordcount'];

    protected $defaultToolbars = ['searchreplace bold italic underline strikethrough alignleft aligncenter alignright outdent indent  blockquote undo redo removeformat subscript superscript code codesample', 'hr bullist numlist link image charmap preview anchor pagebreak insertdatetime media table emoticons forecolor backcolor fullscreen'];

    /**
     * 初始化
     *
     * @time 2021年04月11日
     * @return Editor|void
     */
    protected function init(): Editor
    {
        return $this->plugins()
                    ->toolbars()
                    ->language()
                    ->initContent()
                    ->uploadConf();
    }

    public function createValidate()
    {
        // TODO: Implement createValidate() method.
        return Elm::validateStr();
    }


    /**
     * set plugins
     *
     * @time 2021年04月11日
     * @param array $plugins
     * @return Editor
     */
    public function plugins(array $plugins = []): Editor
    {
        $this->props([
            'plugins' => count($plugins) ? $plugins : $this->defaultPlugins,
        ]);

        return $this;
    }


    /**
     * set toolbars
     *
     * @time 2021年04月11日
     * @param array $toolbars
     * @return Editor
     */
    public function toolbars(array $toolbars = []): Editor
    {
        $this->props([
            'toolbar' => count($toolbars) ? $toolbars : $this->defaultToolbars,
        ]);

        return $this;
    }

    /**
     * 设置语言
     * 支持 'es_MX', 'en', 'zh_CN', 'ja'
     * @time 2021年04月11日
     * @param string $language
     * @return $this
     */
    public function language(string $language = 'zh'): Editor
    {
        $this->props([
            'language' => $language
        ]);

        return $this;
    }


    /**
     * 编辑器高度
     *
     * @time 2021年04月11日
     * @param int $height
     * @return $this
     */
    public function height(int $height = 500): Editor
    {
        $this->props([
            'height' => $height
        ]);

        return $this;
    }

    /**
     * 宽度设置
     *
     * @time 2021年04月11日
     * @param string $width
     * @return $this
     */
    public function width($width = 'auto'): Editor
    {
        $this->props([
            'width' => $width
        ]);

        return $this;
    }

    /**
     * 编辑器默认内容
     *
     * @time 2021年04月11日
     * @param string $content
     * @return $this
     */
    public function initContent(string $content = ''): Editor
    {
        $this->props([
            'initContent' => $content
        ]);

        return $this;
    }

    /**
     * 上传配置
     *
     * @time 2021年04月11日
     * @param int $size
     * @return $this
     */
    public function uploadConf(int $size = 10): Editor
    {
        $this->props([
            'uploadConf' => array_merge([
                'url' => Form::uploadImageUrl(),
                'size' => $size,
            ], Form::authorization())
        ]);

        return $this;
    }
}
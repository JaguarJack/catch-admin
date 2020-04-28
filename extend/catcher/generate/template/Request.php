<?php
namespace catcher\generate\template;

trait Request
{
    /**
     * 规则
     *
     * @time 2020年04月24日
     * @return string
     */
    public function rules()
    {
        return <<<EOT
    public function rules(): array
    {
        return [
            {rules}
        ];
    }    
EOT;

    }

    /**
     * 消息
     *
     * @time 2020年04月24日
     * @return string
     */
    public function message()
    {
        return <<<EOT
    public function message(): array
    {
        return [
            {messages}
        ];
    }    
EOT;
    }
}
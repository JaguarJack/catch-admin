<?php
namespace catcher\library\form;

trait FormValidates
{
    /**
     * 纯数字验证
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateAlpha(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('^[A-Za-z]+$')->message('必须为纯字母');
    }

    /**
     * 字母和数字
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateAlphaNum(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('^[A-Za-z0-9]+$')->message('必须为字母和数字');
    }

    /**
     *
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateAlphaDash(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('^[A-Za-z0-9\-\_]+$')->message('必须为字母和数字，下划线_及破折号-');
    }

    /**
     * 手机号
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateMobile(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('^1[3-9]\d{9}$')->message('请输入正确的手机号格式');
    }

    /**
     * 身份证
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateIdCard(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)')->message('身份证输入格式不正确');
    }

    /**
     * 邮政编码
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateZip(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('\d{6}')->message('请输入有效的邮政编码');
    }

    /**
     * IP 地址
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateIp(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('((?:(?:25[0-5]|2[0-4]\\d|[01]?\\d?\\d)\\.){3}(?:25[0-5]|2[0-4]\\d|[01]?\\d?\\d))')->message('请输入正确的 IP 地址');
    }

    /**
     * 座机
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateLandLine(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('\d{3}-\d{8}|\d{4}-\d{7}')->message('请输入正确的座机格式');
    }

    /**
     * 密码
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validatePassword(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('^[a-zA-Z]\w{5,18}$')->message('以字母开头，长度在6~18之间，只能包含字母、数字和下划线');
    }

    /**
     * 强密码
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateStrongPassword(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$')->message('必须包含大小写字母和数字的组合，不能使用特殊字符，长度在8-20之间');
    }

    /**
     * 纯汉字
     *
     * @time 2021年03月12日
     * @return \FormBuilder\UI\Elm\Validate
     */
    public static function validateChineseCharacter(): \FormBuilder\UI\Elm\Validate
    {
        return Form::validateStr()->pattern('^[\u4e00-\u9fa5]{0,}$')->message('必须为纯汉字');
    }
}
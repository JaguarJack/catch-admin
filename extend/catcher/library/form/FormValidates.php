<?php
namespace catcher\library\form;

use FormBuilder\UI\Elm\Validate;

trait FormValidates
{
    /**
     * 正则验证
     *
     * @time 2021年03月06日
     * @param string $pattern
     * @return Validate
     */
    public static function validatePattern(string $pattern): Validate
    {
        return self::validateStr()->pattern($pattern);
    }

    /**
     * 纯数字验证
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateAlpha(): Validate
    {
        return self::validatePattern('^[A-Za-z]+$')->message('必须为纯字母');
    }

    /**
     * 字母和数字
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateAlphaNum(): Validate
    {
        return self::validatePattern('^[A-Za-z0-9]+$')->message('必须为字母和数字');
    }

    /**
     *
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateAlphaDash(): Validate
    {
        return self::validatePattern('^[A-Za-z0-9\-\_]+$')->message('必须为字母和数字，下划线_及破折号-');
    }

    /**
     * 手机号
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateMobile(): Validate
    {
        return self::validatePattern('^1[3-9]\d{9}$')->message('请输入正确的手机号格式');
    }

    /**
     * 身份证
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateIdCard(): Validate
    {
        return self::validatePattern('(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)')->message('身份证输入格式不正确');
    }

    /**
     * 邮政编码
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateZip(): Validate
    {
        return self::validatePattern('\d{6}')->message('请输入有效的邮政编码');
    }

    /**
     * IP 地址
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateIp(): Validate
    {
        return self::validatePattern('((?:(?:25[0-5]|2[0-4]\\d|[01]?\\d?\\d)\\.){3}(?:25[0-5]|2[0-4]\\d|[01]?\\d?\\d))')->message('请输入正确的 IP 地址');
    }

    /**
     * 座机
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateLandLine(): Validate
    {
        return self::validatePattern('\d{3}-\d{8}|\d{4}-\d{7}')->message('请输入正确的座机格式');
    }

    /**
     * 密码
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validatePassword(): Validate
    {
        return self::validatePattern('^[a-zA-Z]\w{5,18}$')->message('以字母开头，长度在6~18之间，只能包含字母、数字和下划线');
    }

    /**
     * 强密码
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateStrongPassword(): Validate
    {
        return self::validatePattern('^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$')->message('必须包含大小写字母和数字的组合，不能使用特殊字符，长度在8-20之间');
    }

    /**
     * 纯汉字
     *
     * @time 2021年03月12日
     * @return Validate
     */
    public static function validateChineseCharacter(): Validate
    {
        return self::validatePattern('^[\u4e00-\u9fa5]{0,}$')->message('必须为纯汉字');
    }
}
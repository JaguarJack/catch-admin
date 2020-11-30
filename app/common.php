<?php
// 应用公共文件
if (!function_exists('request')) {
    /**
     * 获取当前Request对象实例
     * @return Request
     */
    function request(): \app\Request
    {
        return app('request');
    }
}

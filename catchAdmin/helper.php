<?php

/**
 * 模块 view path
 *
 */

use think\helper\Arr;

if (!function_exists('getModuleViewPath()')) {
    function getModuleViewPath($module) {

       if (file_exists($views = app()->getRuntimePath() . 'module' . DIRECTORY_SEPARATOR . 'view.php')) {
           $views =  include $views;

           return $views[$module];
       } else {

       }
       return '';
    }
}

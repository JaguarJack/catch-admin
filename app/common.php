<?php
// 应用公共文件


function is_empty($value)
{
    if ($value == '')
        return true;
    if ($value == "")
        return true;
    if ($value == null)
        return true;
    if ($value == false)
        return true;
    if ($value == "false")
        return true;
    if ($value == 'false')
        return true;
    if (empty($value))
        return true;
    if (isset($value) == false)
        return true;
    if ($value == ' ')
        return true;
    if ($value == " ")
        return true;
    if ($value == "[]")
        return true;
    if ($value == "()")
        return true;
    if ($value == "{}")
        return true;
    return false;
}

<?php

function editButton($name = '修改', $event = 'edit')
{
    return sprintf(
        '<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="%s">%s</a>',
        $event, $name);
}

function deleteButton($name = '删除', $event = 'del')
{
    return sprintf(
        '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="%s">%s</a>',
        $event, $name);
}

function addButton($name = '新增', $event = 'add')
{
    return sprintf(
        '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="%s">%s</a>',
        $event, $name);
}


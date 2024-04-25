<?php

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

return new class extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run(): void
    {
        $menus = $this->menus();

        importTreeData($menus, 'permissions');
    }

    public function menus(): array
    {
        return array (
  0 =>
  array (
    'id' => 96,
    'parent_id' => 0,
    'permission_name' => '系统管理',
    'route' => '/system',
    'icon' => 'server-stack',
    'module' => 'system',
    'permission_mark' => '',
    'component' => '',
    'redirect' => NULL,
    'keepalive' => 1,
    'type' => 1,
    'hidden' => 1,
    'sort' => 1,
    'active_menu' => '',
    'creator_id' => 1,
    'created_at' => 1683535826,
    'updated_at' => 1683535826,
    'deleted_at' => 0,
  ),
  1 =>
  array (
    'id' => 97,
    'parent_id' => 96,
    'permission_name' => '字典管理',
    'route' => 'dictionary',
    'icon' => '',
    'module' => 'system',
    'permission_mark' => 'dictionary',
    'component' => '/system/dictionary/index.vue',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 2,
    'hidden' => 1,
    'sort' => 1,
    'active_menu' => '',
    'creator_id' => 1,
    'created_at' => 1683535863,
    'updated_at' => 1683535874,
    'deleted_at' => 0,
  ),
  2 =>
  array (
    'id' => 103,
    'parent_id' => 97,
    'permission_name' => '删除',
    'route' => '',
    'icon' => '',
    'module' => 'system',
    'permission_mark' => 'dictionary@destroy',
    'component' => '',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 3,
    'hidden' => 1,
    'sort' => 5,
    'active_menu' => '',
    'creator_id' => 1,
    'created_at' => 1683535980,
    'updated_at' => 1683535980,
    'deleted_at' => 0,
  ),
  3 =>
  array (
    'id' => 99,
    'parent_id' => 97,
    'permission_name' => '列表',
    'route' => '',
    'icon' => '',
    'module' => 'system',
    'permission_mark' => 'dictionary@index',
    'component' => '',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 3,
    'hidden' => 1,
    'sort' => 1,
    'active_menu' => '',
    'creator_id' => 1,
    'created_at' => 1683535980,
    'updated_at' => 1683535980,
    'deleted_at' => 0,
  ),
  4 =>
  array (
    'id' => 101,
    'parent_id' => 97,
    'permission_name' => '读取',
    'route' => '',
    'icon' => '',
    'module' => 'system',
    'permission_mark' => 'dictionary@show',
    'component' => '',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 3,
    'hidden' => 1,
    'sort' => 3,
    'active_menu' => '',
    'creator_id' => 1,
    'created_at' => 1683535980,
    'updated_at' => 1683535980,
    'deleted_at' => 0,
  ),
  5 =>
  array (
    'id' => 100,
    'parent_id' => 97,
    'permission_name' => '新增',
    'route' => '',
    'icon' => '',
    'module' => 'system',
    'permission_mark' => 'dictionary@store',
    'component' => '',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 3,
    'hidden' => 1,
    'sort' => 2,
    'active_menu' => '',
    'creator_id' => 1,
    'created_at' => 1683535980,
    'updated_at' => 1683535980,
    'deleted_at' => 0,
  ),
  6 =>
  array (
    'id' => 102,
    'parent_id' => 97,
    'permission_name' => '更新',
    'route' => '',
    'icon' => '',
    'module' => 'system',
    'permission_mark' => 'dictionary@update',
    'component' => '',
    'redirect' => '',
    'keepalive' => 1,
    'type' => 3,
    'hidden' => 1,
    'sort' => 4,
    'active_menu' => '',
    'creator_id' => 1,
    'created_at' => 1683535980,
    'updated_at' => 1683535980,
    'deleted_at' => 0,
  ),
  7 =>
  array (
    'id' => 98,
    'parent_id' => 96,
    'permission_name' => '字典值管理',
    'route' => 'dictionary/values/:id',
    'icon' => '',
    'module' => 'system',
    'permission_mark' => 'dictionaryValues',
    'component' => '/system/dictionaryValues/index.vue',
    'redirect' => '',
    'keepalive' => 2,
    'type' => 2,
    'hidden' => 2,
    'sort' => 1,
    'active_menu' => '/system/dictionary',
    'creator_id' => 1,
    'created_at' => 1683535961,
    'updated_at' => 1683593856,
    'deleted_at' => 0,
  ),
);
    }
};

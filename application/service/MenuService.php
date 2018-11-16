<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/13 0013
 * Time: 上午 10:50
 */
namespace app\service;

use think\Collection;

class MenuService
{

	/**
	 * 树形结构
	 *
	 * @time at 2018年11月13日
	 * @param $menu
	 * @return Collection
	 */
	public function tree(Collection $menus, int $pid = 0)
	{
		$collection = new Collection();

		$menus->each(function ($item, $key) use ($pid, $menus, $collection){
				if ($item->pid == $pid) {
					$collection[$key] = $item;
					$collection[$key][$item->id] = $this->tree($menus, $item->id);
				}
		});

		return $collection;
	}

	/**
	 * 顺序结构
	 *
	 * @time at 2018年11月13日
	 * @param $menu
	 * @return Collection
	 */
	public function sort(Collection $menus, int $pid = 0, int $level = 0)
	{
		$collection = [];
		foreach ($menus as $menu) {
			if ($menu->pid == $pid) {
				$menu->level = $level;
				$collection[] = $menu;
				$collection  = array_merge($collection, $this->sort($menus, $menu->id, $level+1));
			}
		}
		return $collection;
	}
}
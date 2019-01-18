<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12 0012
 * Time: 上午 11:05
 */
namespace app\model;

use think\Model;

class BaseModel extends Model
{
	const LIMIT  = 10;

	/**
	 * Store Data
	 *
	 * @time at 2018年11月12日
	 * @param array $data
	 * @return bool
	 */
	public function store(array $data)
	{
		return $this->save($data) ? $this->id : false;
	}

	/**
	 * Find By ID
	 *
	 * @time at 2018年11月12日
	 * @param int $id
	 * @return array|false|\PDOStatement|string|\think\Model
	 */
	public function findBy(int $id)
	{
		return $this->where('id', $id)->find();
	}

	/**
	 * Update By ID && Data
	 *
	 * @time at 2018年11月12日
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
	public function updateBy(int $id, array $data)
	{
		return $this->save($data, ['id' => $id]);
	}

	/**
	 * Delete By ID
	 *
	 * @time at 2018年11月12日
	 * @param int $id
	 * @return bool|null
	 */
	public function deleteBy(int $id)
	{
		return $this->where('id', $id)->delete();
	}
}
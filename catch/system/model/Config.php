<?php
namespace catchAdmin\system\model;

use catcher\base\CatchModel;
use thans\jwt\exception\UserNotDefinedException;
use think\Model;

class Config extends CatchModel
{
    protected $name = 'config';

    protected $pk = 'id';

    protected $field = [
        'id', // 
		'name', // 配置名称
		'pid', // 父级配置
		'key', // 配置键名
		'value', // 配置键值
        'component', // 组件
		'status', // 1 启用 2 禁用
		'creator_id', // 创建人
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 删除时间   
    ];

    /**
     *
     * @time 2020年04月17日
     * @return \think\Collection
     *@throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function getParentConfig()
    {
        return $this->where('pid', 0)
                    ->field(['id', 'name', 'component'])->select();
    }

    /**
     * 存储配置
     *
     * @time 2020年04月20日
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return bool
     */
    public function storeBy(array $data)
    {
        if (empty($data)) {
            return true;
        }

        // 子配置
        if ($data['pid'] ?? false) {
            $config = \json_decode($data['config'], true);
            $pid = $data['pid'];
            unset($data['pid']);
            /**[
                'key' => [
                    'k' => 'v'
                ],

                'k' => 'v'
            ]*/
            foreach ($config as $key => $value) {
                if (empty($value)) {
                    continue;
                }
                // 如果二级配置存在
                $secondLevel = $this->isExistConfig($key, $pid);
                if ($secondLevel) {
                    // value 是字符串
                    if (!is_array($value)) {
                        if ($value != $secondLevel->value) {
                            $secondLevel->value = $value;
                            $secondLevel->save();
                        }
                    } else {
                        // 数组
                        $thirdLevel = [];
                        $this->subConfig($secondLevel->id, ['id', 'key', 'value'])
                             ->each(function ($item, $key) use (&$thirdLevel){
                                 $thirdLevel[$item['key']] = $item;
                             });

                        if (!empty($value)) {
                            $new = [];
                            foreach ($value as $k => $v) {
                                if (isset($thirdLevel[$k])) {
                                    if ($v != $thirdLevel[$k]->value) {
                                        $thirdLevel[$k]->value = $v;
                                        $thirdLevel[$k]->save();
                                    }
                                } else {
                                    $new[] = [
                                        'pid' => $secondLevel->id,
                                        'key' => $k,
                                        'value' => $v,
                                    ];
                                }
                            }

                            if (!empty($new)) {
                                parent::insertAllBy($new);
                            }
                        }
                    }
                } else {
                    if (!is_array($value)) {
                        parent::createBy([
                            'pid' => $pid,
                            'key' => $key,
                            'value' => $value,
                        ]);
                    } else {
                        $id = parent::createBy([
                            'pid' => $pid,
                            'key' => $key,
                        ]);
                        if (!empty($value)) {
                            $newConfig = [];
                            foreach ($value as $k => $v) {
                                $newConfig[] = [
                                    'key' => $k,
                                    'value' => $v,
                                    'pid' => $id,
                                ];
                            }
                            parent::insertAllBy($newConfig);
                        }
                    }
                }
            }

            return true;
        }

        return parent::storeBy($data);
    }

    /**
     * 配置是否存在
     *
     * @time 2020年04月19日
     * @param $key
     * @param int $pid
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|Model|null
     */
    public function isExistConfig($key, $pid = 0)
    {
        return $this->where('pid', $pid)
                    ->where('key', $key)
                    ->find();
    }

    /**
     * 获取子配置
     *
     * @time 2020年04月19日
     * @param int $pid
     * @param array $field
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return \think\Collection
     */
    public function subConfig($pid = 0, array $field = ['*'])
    {
        return $this->where('pid', $pid)
                    ->field($field)
                    ->select();
    }

    /**
     * 获取配置
     *
     * @time 2020年04月20日
     * @param int $pid
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|mixed
     */
    public function getConfig($pid = 0)
    {
        $data = [];

        $configs = $this->where('pid', $pid)
                      ->field('id,`key` as k,value,pid')
                      ->select();

        foreach ($configs as $config) {
            if ($config->value !== '') {
                $data[$config->k] = $config->value;
            } else {
                $data[$config->k] = $this->getConfig($config->id);
            }
        }

        return empty($data) ? '' : $data;
    }
}
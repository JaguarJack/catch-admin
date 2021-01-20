<?php
namespace catchAdmin\system\model;

use catcher\base\CatchModel;
use catcher\exceptions\FailedException;
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

        $parent = $data['parent'] ?? false;
        if (!$parent) {
            throw new FailedException('父配置丢失');
        }
        unset($data['parent']);

        $parentConfig = $this->where('key', $parent)->find();
        $config = [];
        foreach ($data as $key => $item) {
            foreach ($item as $k => $value) {
                $config[$key . '.' .$k] = [
                    'pid' => $parentConfig['id'],
                    'key' => $key . '.' . $k,
                    'value' => $value,
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
            }
        }

        $this->where('pid', $parentConfig->id)
           ->select()
           ->each(function ($item) use (&$config){
               if (isset($config[$item['key']])) {
                  if ($config[$item['key']]['value'] != $item->value) {
                      $item['value'] = $config[$item['key']]['value'];
                      $item->save();
                  }
                   unset($config[$item['key']]);
              }
           });

        if (count($config)) {
            return $this->insertAll($config);
        }

        return true;
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
     * @param string $component
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|mixed
     */
    public function getConfig(string $component)
    {
        $data = [];
        $configs = $this->where('pid', $this->where('component', $component)->value('id'))
                      ->field('id,`key` as k,value,pid')
                      ->select();

        foreach ($configs as $config) {
            if (strpos($config['k'], '.') !== false) {
                list($object, $key) = explode('.', $config['k']);
                $data[$object][$key] = $config['value'];
            }
        }

        return $data;
    }
}
<?php

namespace catchAdmin\config\model;

use catcher\base\CatchModel as Model;
use catcher\exceptions\FailedException;

// 数据库字段映射
class FinanceConfig extends Model
{
    // 表名
    public $name = 'finance_config';
    public $field = array(
        'id',
        // 配置描述
        'remark',
        // 配置key
        'key',
        // 配置值
        'value',
        // 是否必须 0 否 1是
        'force',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
    );
    // $this->startTrans();

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
        $creator_id = $data['creator_id'];
        unset($data['creator_id']);
        $config = [];
        foreach ($data as $key => $item) {
            if ($item) {
                $config[$item['key']] = [
                    'key' => $item['key'],
                    'value' => $item['value'],
                    'remark' => $item['remark'],
                    'force' => $item['force'],
                    'creator_id' => $creator_id,
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
            }
        }
        // return $config;
        $this->select()->each(function ($item) use (&$config) {
            // var_dump($config[$item['key']]);
            // exit;
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
}
